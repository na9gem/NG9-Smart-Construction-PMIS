<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoSCurveSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            $projectCode = 'DEMO-SCURVE-001';

            // Prevent duplicate demo data
            if (DB::table('projects')
                ->where('project_code', $projectCode)
                ->exists()) {

                $this->command->warn(
                    "Demo project {$projectCode} already exists. Nothing was created."
                );

                return;
            }

            /*
             * ---------------------------------------------------------
             * 1. PROJECT
             * ---------------------------------------------------------
             */
            $projectId = DB::table('projects')->insertGetId([
                'project_code' => $projectCode,
                'project_name' => 'Demo โครงการทดสอบ S-Curve NG9',
                'owner' => 'มหาวิทยาลัยธรรมศาสตร์',
                'contractor' => 'Demo Construction Co., Ltd.',
                'consultant' => 'Demo Consultant',
                'location' => 'ศูนย์รังสิต',
                'budget' => 50000000,
                'contract_number' => 'DEMO-C-001/2026',
                'contract_amount' => 50000000,
                'progress_percent' => 0,
                'status' => 'Construction',
                'planned_start_date' => '2026-08-10',
                'planned_finish_date' => '2027-08-10',
                'actual_finish_date' => null,
                'created_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            /*
             * ---------------------------------------------------------
             * 2. CONTRACT
             * ---------------------------------------------------------
             */
            $contractId = DB::table('contracts')->insertGetId([
                'project_id' => $projectId,
                'contract_no' => 'DEMO-C-001/2026',
                'contract_date' => '2026-08-08',
                'employer' => 'มหาวิทยาลัยธรรมศาสตร์',
                'contractor_signer' => 'Demo Construction Co., Ltd.',
                'contract_amount' => 50000000,
                'contract_days' => 365,
                'start_date' => '2026-08-10',
                'finish_date' => '2027-08-10',
                'extended_finish_date' => null,
                'extension_days' => 0,
                'extension_reason' => null,
                'performance_bond' => 2500000,
                'retention_percent' => 5,
                'penalty_per_day' => 50000,
                'procurement_method' => 'Demo',
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            /*
             * ---------------------------------------------------------
             * 3. MILESTONES
             * ---------------------------------------------------------
             */
            $milestones = [
                [
                    'no' => 1,
                    'name' => 'งวดที่ 1 - เตรียมงานและเริ่มต้นโครงการ',
                    'description' => 'Mobilization and preparation',
                    'start' => '2026-08-10',
                    'finish' => '2026-09-10',
                    'payment_percent' => 20,
                    'payment_amount' => 10000000,
                ],
                [
                    'no' => 2,
                    'name' => 'งวดที่ 2 - งานก่อสร้างหลัก',
                    'description' => 'Main construction works',
                    'start' => '2026-09-11',
                    'finish' => '2026-11-10',
                    'payment_percent' => 40,
                    'payment_amount' => 20000000,
                ],
                [
                    'no' => 3,
                    'name' => 'งวดที่ 3 - งานระบบและเก็บรายละเอียด',
                    'description' => 'MEP and finishing works',
                    'start' => '2026-11-11',
                    'finish' => '2027-02-10',
                    'payment_percent' => 40,
                    'payment_amount' => 20000000,
                ],
            ];

            $milestoneIds = [];

            foreach ($milestones as $milestone) {
                $milestoneIds[$milestone['no']] =
                    DB::table('milestones')->insertGetId([
                        'contract_id' => $contractId,
                        'milestone_no' => $milestone['no'],
                        'milestone_name' => $milestone['name'],
                        'description' => $milestone['description'],
                        'planned_start_date' => $milestone['start'],
                        'planned_finish_date' => $milestone['finish'],
                        'payment_percent' => $milestone['payment_percent'],
                        'payment_amount' => $milestone['payment_amount'],
                        'status' => 'Approved',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
            }

            /*
             * ---------------------------------------------------------
             * 4. WORK PACKAGES
             * ---------------------------------------------------------
             */
            $workPackages = [
                [1, 'WP-01', 'Mobilization'],
                [2, 'WP-02', 'Site Preparation'],
                [3, 'WP-03', 'Main Structure'],
                [4, 'WP-04', 'Architectural Works'],
                [5, 'WP-05', 'MEP Works'],
                [6, 'WP-06', 'Testing and Handover'],
            ];

            $workPackageIds = [];

            foreach ($workPackages as $index => $wp) {

                $milestoneNo = match ($index) {
                    0, 1 => 1,
                    2, 3 => 2,
                    default => 3,
                };

                $workPackageIds[$wp[1]] =
                    DB::table('work_packages')->insertGetId([
                        'milestone_id' => $milestoneIds[$milestoneNo],
                        'package_code' => $wp[1],
                        'package_name' => $wp[2],
                        'description' => null,
                        'sequence_no' => $index + 1,
                        'status' => 'Approved',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
            }

            /*
             * ---------------------------------------------------------
             * 5. ACTIVITIES
             *
             * Weight total = 100%
             * ---------------------------------------------------------
             */
            $activities = [
                [
                    'package' => 'WP-01',
                    'code' => 'ACT-001',
                    'name' => 'Mobilization',
                    'start' => '2026-08-10',
                    'finish' => '2026-08-31',
                    'weight' => 10,
                ],
                [
                    'package' => 'WP-02',
                    'code' => 'ACT-002',
                    'name' => 'Site Preparation',
                    'start' => '2026-08-25',
                    'finish' => '2026-09-30',
                    'weight' => 10,
                ],
                [
                    'package' => 'WP-03',
                    'code' => 'ACT-003',
                    'name' => 'Main Structure',
                    'start' => '2026-09-01',
                    'finish' => '2026-10-31',
                    'weight' => 20,
                ],
                [
                    'package' => 'WP-04',
                    'code' => 'ACT-004',
                    'name' => 'Architectural Works',
                    'start' => '2026-10-01',
                    'finish' => '2026-11-30',
                    'weight' => 20,
                ],
                [
                    'package' => 'WP-05',
                    'code' => 'ACT-005',
                    'name' => 'MEP Works',
                    'start' => '2026-11-01',
                    'finish' => '2027-01-31',
                    'weight' => 15,
                ],
                [
                    'package' => 'WP-06',
                    'code' => 'ACT-006',
                    'name' => 'Testing and Handover',
                    'start' => '2026-12-01',
                    'finish' => '2027-02-10',
                    'weight' => 25,
                ],
            ];

            $activityIds = [];

            foreach ($activities as $activity) {
                $activityIds[$activity['code']] =
                    DB::table('activities')->insertGetId([
                        'work_package_id' => $workPackageIds[$activity['package']],
                        'activity_code' => $activity['code'],
                        'activity_name' => $activity['name'],
                        'description' => null,
                        'planned_start_date' => $activity['start'],
                        'planned_finish_date' => $activity['finish'],
                        'actual_start_date' => null,
                        'actual_finish_date' => null,
                        'weight' => $activity['weight'],
                        'status' => 'Approved',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
            }

            /*
             * ---------------------------------------------------------
             * 6. ACTIVITY DEPENDENCIES
             * ---------------------------------------------------------
             */
            $dependencies = [
                ['ACT-001', 'ACT-002'],
                ['ACT-002', 'ACT-003'],
                ['ACT-003', 'ACT-004'],
                ['ACT-004', 'ACT-005'],
                ['ACT-004', 'ACT-006'],
            ];

            foreach ($dependencies as [$predecessor, $successor]) {
                DB::table('activity_dependencies')->insert([
                    'predecessor_activity_id' => $activityIds[$predecessor],
                    'successor_activity_id' => $activityIds[$successor],
                    'dependency_type' => 'FS',
                    'lag_days' => 0,
                    'description' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            /*
             * ---------------------------------------------------------
             * 7. BASELINE PROGRESS PLAN
             * ---------------------------------------------------------
             */
            $planId = DB::table('progress_plans')->insertGetId([
                'contract_id' => $contractId,
                'plan_name' => 'Demo Baseline S-Curve',
                'plan_type' => 'Baseline',
                'version' => '01',
                'effective_date' => '2026-08-10',
                'source_document_id' => null,
                'is_baseline' => true,
                'status' => 'Approved',
                'created_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

                  /*
 * 8. BASELINE PROGRESS ITEMS
 *
 * planned_percent = incremental progress of each activity
 * Activity weight is derived from the Activity model.
 */
               $planDates = [
                '2026-08-10',
                '2026-09-10',
                '2026-10-10',
                '2026-11-10',
                '2026-12-10',
            ];

            $progress = [
                'ACT-001' => [70, 30, 0, 0, 0],
                'ACT-002' => [0, 50, 50, 0, 0],
                'ACT-003' => [0, 20, 40, 40, 0],
                'ACT-004' => [0, 0, 20, 40, 40],
                'ACT-005' => [0, 0, 0, 40, 60],
                'ACT-006' => [0, 0, 0, 20, 80],
            ];


            foreach ($progress as $activityCode => $increments) {


                foreach ($increments as $index => $increment) {


                    DB::table('progress_plan_items')->insert([
                         'progress_plan_id' => $planId,
'activity_id' => $activityIds[$activityCode],
'plan_date' => $planDates[$index],
'planned_percent' => $increment,
'created_at' => now(),
'updated_at' => now(),
                    ]);
                }
            }

            $this->command->info('Demo S-Curve dataset created successfully.');

            $this->command->info("Project ID: {$projectId}");
            $this->command->info("Contract ID: {$contractId}");
            $this->command->info("Baseline Plan ID: {$planId}");
        });
    }
}
