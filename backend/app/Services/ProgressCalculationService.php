<?php

namespace App\Services;

use App\Models\Project;
use App\Models\ProgressPlan;
use App\Models\ProgressReport;
use Illuminate\Support\Collection;

class ProgressCalculationService
{
    /**
     * Calculate project progress summary and S-Curve data.
     */
    public function calculate(Project $project): array
    {
        $plan = ProgressPlan::query()
            ->whereHas('contract', function ($query) use ($project) {
                $query->where('project_id', $project->id);
            })
            ->where('is_baseline', true)
            ->with('items')
            ->orderByDesc('effective_date')
            ->first();

        if (!$plan) {
            return [
                'planned' => 0,
                'actual' => 0,
                'variance' => 0,
                's_curve' => [],
                'actual_s_curve' => [],
            ];
        }

        /*
         * Planned S-Curve
         */
        $sCurve = $this->calculateSCurve($plan->items);

        /*
         * Planned progress ณ วันที่ปัจจุบัน
         */
        $planned = $this->calculatePlannedProgressAsOfToday(
            $plan->items
        );

        /*
         * Actual Progress
         *
         * ใช้เฉพาะ Progress Report ที่ Approved
         * และใช้รายงานล่าสุดเป็น Actual ปัจจุบัน
         */
        $approvedReports = ProgressReport::query()
            ->where('project_id', $project->id)
            ->where('status', 'Approved')
            ->orderBy('report_date')
            ->orderBy('id')
            ->get();

        $actual = $approvedReports->isNotEmpty()
            ? (float) $approvedReports->last()->progress_percent
            : 0;

        /*
         * Actual S-Curve
         *
         * ใช้ progress_percent ของแต่ละ Approved Report
         * เป็นค่าความก้าวหน้าสะสม ณ วันที่รายงาน
         */
        $actualSCurve = $approvedReports
            ->map(function (ProgressReport $report) {
                return [
                    'date' => $report->report_date->format('Y-m-d'),
                    'actual' => round(
                        (float) $report->progress_percent,
                        2
                    ),
                ];
            })
            ->values()
            ->all();

        return [
            'planned' => round($planned, 2),
            'actual' => round($actual, 2),
            'variance' => round($actual - $planned, 2),
            's_curve' => $sCurve,
            'actual_s_curve' => $actualSCurve,
        ];
    }

    /**
     * Calculate Planned S-Curve from baseline progress plan items.
     *
     * planned_percent is incremental progress of an activity.
     * planned_weight is the activity's weight in the project.
     */
    protected function calculateSCurve(Collection $items): array
    {
        if ($items->isEmpty()) {
            return [];
        }

        /*
         * Calculate weighted progress for every plan date.
         */
        $dailyProgress = $items
            ->groupBy(function ($item) {
                return $item->plan_date->format('Y-m-d');
            })
            ->map(function (Collection $dateItems) {
                return $dateItems->sum(function ($item) {
                    return ((float) $item->planned_weight)
                        * ((float) $item->planned_percent)
                        / 100;
                });
            })
            ->sortKeys();

        /*
         * Convert incremental progress
         * into cumulative progress.
         */
        $cumulative = 0;

        return $dailyProgress
            ->map(function ($value, $date) use (&$cumulative) {
                $cumulative += (float) $value;

                return [
                    'date' => $date,
                    'planned' => round($cumulative, 2),
                ];
            })
            ->values()
            ->all();
    }

    /**
     * Calculate planned progress as of today.
     *
     * Uses the latest plan date that is not after today.
     */
    protected function calculatePlannedProgressAsOfToday(
        Collection $items
    ): float {
        if ($items->isEmpty()) {
            return 0;
        }

        $today = now()->startOfDay();

        $validItems = $items->filter(function ($item) use ($today) {
            return $item->plan_date
                ->startOfDay()
                ->lte($today);
        });

        if ($validItems->isEmpty()) {
            return 0;
        }

        $sCurve = $this->calculateSCurve($validItems);

        if (empty($sCurve)) {
            return 0;
        }

        return (float) last($sCurve)['planned'];
    }
}
