<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Contract;
use App\Models\Document;
use App\Models\ProgressReport;
use App\Models\Inspection;
use App\Models\Media;
use App\Models\Milestone;
use App\Services\ProgressCalculationService;

class DashboardController extends Controller
{
    /**
     * Dashboard Summary
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'Dashboard Summary',
            'data' => [
                'projects' => Project::count(),
                'contracts' => Contract::count(),
                'documents' => Document::count(),
                'progress_reports' => ProgressReport::count(),
                'open_inspections' => Inspection::where('status', 'Open')->count(),
                'media' => Media::count(),
            ],
        ]);
    }

    /**
     * Project Dashboard / S-Curve
     */
    public function sCurve(
        Project $project,
        ProgressCalculationService $progressCalculationService
    ) {
        $calculation = $progressCalculationService->calculate($project);

        $contract = Contract::where('project_id', $project->id)
            ->orderByDesc('id')
            ->first();

        $milestones = collect();

        if ($contract) {
            $milestones = Milestone::where('contract_id', $contract->id)
                ->orderBy('milestone_no')
                ->get([
                    'id',
                    'milestone_no',
                    'milestone_name',
                    'planned_start_date',
                    'planned_finish_date',
                    'payment_percent',
                    'payment_amount',
                    'status',
                ]);
        }

        $documentCount = Document::where('project_id', $project->id)->count();

        $progressReportCount = ProgressReport::where(
            'project_id',
            $project->id
        )->count();

        $latestApprovedProgress = ProgressReport::where(
            'project_id',
            $project->id
        )
            ->where('status', 'Approved')
            ->orderByDesc('report_date')
            ->first();

        $openInspections = Inspection::where(
            'project_id',
            $project->id
        )
            ->where('status', 'Open')
            ->count();

        $actual = $latestApprovedProgress
            ? (float) $latestApprovedProgress->progress_percent
            : 0;

        $variance = $actual - (float) $calculation['planned'];

        return response()->json([
            'success' => true,
            'message' => 'Project Dashboard',
            'data' => [
                'project' => [
                    'id' => $project->id,
                    'project_code' => $project->project_code,
                    'project_name' => $project->project_name,
                    'status' => $project->status,
                    'location' => $project->location,
                    'owner' => $project->owner,
                    'contractor' => $project->contractor,
                    'consultant' => $project->consultant,
                    'budget' => $project->budget,
                    'contract_amount' => $project->contract_amount,
                    'planned_start_date' => $project->planned_start_date,
                    'planned_finish_date' => $project->planned_finish_date,
                    'actual_finish_date' => $project->actual_finish_date,
                ],

                'contract' => $contract ? [
                    'id' => $contract->id,
                    'contract_no' => $contract->contract_no,
                    'contract_date' => $contract->contract_date,
                    'contract_amount' => $contract->contract_amount,
                    'contract_days' => $contract->contract_days,
                    'start_date' => $contract->start_date,
                    'finish_date' => $contract->finish_date,
                    'extended_finish_date' => $contract->extended_finish_date,
                    'status' => $contract->status,
                ] : null,

                'progress' => [
                    'planned' => round((float) $calculation['planned'], 2),
                    'actual' => round($actual, 2),
                    'variance' => round($variance, 2),

                    's_curve' => $calculation['s_curve'],
                    'actual_s_curve' => $calculation['actual_s_curve'],
],
                'milestones' => [
                    'total' => $milestones->count(),
                    'items' => $milestones,
                ],

                'documents' => [
                    'total' => $documentCount,
                ],

                'progress_reports' => [
                    'total' => $progressReportCount,
                    'latest_approved' => $latestApprovedProgress ? [
                        'id' => $latestApprovedProgress->id,
                        'report_date' => $latestApprovedProgress->report_date,
                        'progress_percent' => $latestApprovedProgress->progress_percent,
                        'work_description' => $latestApprovedProgress->work_description,
                    ] : null,
                ],

                'inspections' => [
                    'open' => $openInspections,
                ],
            ],
        ]);
    }
}
