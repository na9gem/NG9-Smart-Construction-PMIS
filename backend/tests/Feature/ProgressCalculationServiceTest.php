<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Contract;
use App\Models\Milestone;
use App\Models\ProgressPlan;
use App\Models\ProgressPlanItem;
use App\Models\Project;
use App\Models\WorkPackage;
use App\Services\ProgressCalculationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgressCalculationServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_activity_weight_is_used_as_source_of_truth(): void
    {
        $project = Project::create([
            'project_code' => 'TEST-PD004-001',
            'project_name' => 'PD-004 Test Project',
            'owner' => 'Test Owner',
        ]);

        $contract = Contract::create([
            'project_id' => $project->id,
            'contract_no' => 'TEST-CONTRACT-001',
            'contract_date' => '2026-08-12',
            'contract_amount' => 1000000,
            'contract_days' => 100,
            'start_date' => '2026-08-01',
            'finish_date' => '2026-11-08',
            'status' => 'Active',
        ]);
        $milestone = new Milestone();
$milestone->contract_id = $contract->id;
$milestone->milestone_no = 1;
$milestone->milestone_name = 'Test Milestone';
$milestone->save();
$workPackage = new WorkPackage();
$workPackage->milestone_id = $milestone->id;
$workPackage->package_code = 'WP-001';
$workPackage->package_name = 'Test Work Package';
$workPackage->save();
$activityA = new Activity();
$activityA->work_package_id = $workPackage->id;
$activityA->activity_code = 'ACT-001';
$activityA->activity_name = 'Activity A';
$activityA->weight = 60;
$activityA->save();
$activityB = new Activity();
$activityB->work_package_id = $workPackage->id;
$activityB->activity_code = 'ACT-002';
$activityB->activity_name = 'Activity B';
$activityB->weight = 40;
$activityB->save();
        $progressPlan = ProgressPlan::create([
            'contract_id' => $contract->id,
            'plan_name' => 'Test Baseline Plan',
            'plan_type' => 'Baseline',
            'version' => '01',
            'effective_date' => '2026-08-12',
            'is_baseline' => true,
            'status' => 'Approved',
        ]);

        ProgressPlanItem::create([
            'progress_plan_id' => $progressPlan->id,
            'activity_id' => $activityA->id,
            'plan_date' => '2026-08-12',
            'planned_percent' => 50,
        ]);

        ProgressPlanItem::create([
            'progress_plan_id' => $progressPlan->id,
            'activity_id' => $activityB->id,
            'plan_date' => '2026-08-12',
            'planned_percent' => 50,
        ]);

        $service = app(ProgressCalculationService::class);

        $result = $service->calculate($project);

        $this->assertSame(50.0, $result['planned']);
        $this->assertSame(50.0, $result['s_curve'][0]['planned']);
    }
    public function test_cumulative_planned_progress_is_calculated_in_date_order(): void
    {
        $project = Project::create([
            'project_code' => 'TEST-PD004-002',
            'project_name' => 'PD-004 Cumulative Test',
            'owner' => 'Test Owner',
        ]);

        $contract = Contract::create([
            'project_id' => $project->id,
            'contract_no' => 'TEST-CONTRACT-002',
            'contract_date' => '2026-08-12',
            'contract_amount' => 1000000,
            'contract_days' => 100,
            'start_date' => '2026-08-01',
            'finish_date' => '2026-11-08',
            'status' => 'Active',
        ]);

        $milestone = new Milestone();
        $milestone->contract_id = $contract->id;
        $milestone->milestone_no = 1;
        $milestone->milestone_name = 'Test Milestone';
        $milestone->save();

        $workPackage = new WorkPackage();
        $workPackage->milestone_id = $milestone->id;
        $workPackage->package_code = 'WP-002';
        $workPackage->package_name = 'Test Work Package';
        $workPackage->save();

        $activity = new Activity();
        $activity->work_package_id = $workPackage->id;
        $activity->activity_code = 'ACT-003';
        $activity->activity_name = 'Activity C';
        $activity->weight = 100;
        $activity->save();

        $progressPlan = ProgressPlan::create([
            'contract_id' => $contract->id,
            'plan_name' => 'Test Cumulative Plan',
            'plan_type' => 'Baseline',
            'version' => '01',
            'effective_date' => '2026-08-12',
            'is_baseline' => true,
            'status' => 'Approved',
        ]);

        ProgressPlanItem::create([
            'progress_plan_id' => $progressPlan->id,
            'activity_id' => $activity->id,
            'plan_date' => '2026-08-12',
            'planned_percent' => 20,
        ]);

        ProgressPlanItem::create([
            'progress_plan_id' => $progressPlan->id,
            'activity_id' => $activity->id,
            'plan_date' => '2026-08-13',
            'planned_percent' => 30,
        ]);

        ProgressPlanItem::create([
            'progress_plan_id' => $progressPlan->id,
            'activity_id' => $activity->id,
            'plan_date' => '2026-08-14',
            'planned_percent' => 50,
        ]);

        $service = app(ProgressCalculationService::class);

        $result = $service->calculate($project);

        $this->assertSame(
            [
                [
                    'date' => '2026-08-12',
                    'planned' => 20.0,
                ],
                [
                    'date' => '2026-08-13',
                    'planned' => 50.0,
                ],
                [
                    'date' => '2026-08-14',
                    'planned' => 100.0,
                ],
            ],
            $result['s_curve']
        );
    }
}
