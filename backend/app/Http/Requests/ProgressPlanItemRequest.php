<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgressPlanItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [


            'activity_id' => [
                'required',
                'exists:activities,id',
            ],

            'plan_date' => [
                'required',
                'date',
            ],

            'planned_percent' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

        ];

    }
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {

            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $progressPlan = $this->route('progressPlan');

            if (!$progressPlan) {
                return;
            }

            $activityId = $this->input('activity_id');
            $plannedPercent = (float) $this->input('planned_percent');

            if (!$activityId || $plannedPercent < 0) {
                return;
            }

            $query = \App\Models\ProgressPlanItem::query()
                ->where('progress_plan_id', $progressPlan->id)
                ->where('activity_id', $activityId);

            $progressPlanItem = $this->route('progressPlanItem');

            if ($progressPlanItem) {
                $query->where('id', '!=', $progressPlanItem->id);
            }

            $existingTotal = (float) $query->sum('planned_percent');

            $newTotal = $existingTotal + $plannedPercent;

            if ($newTotal > 100) {
                $validator->errors()->add(
                    'planned_percent',
                    'ผลรวม Planned Incremental Progress ของ Activity นี้ใน Progress Plan Version นี้ต้องไม่เกิน 100%'
                );
            }
        });
    }
    public function messages(): array
    {
        return [


            'activity_id.required' =>
                'กรุณาเลือกกิจกรรม',

            'activity_id.exists' =>
                'ไม่พบกิจกรรม',

            'plan_date.required' =>
                'กรุณาระบุวันที่ตามแผน',

            'planned_percent.required' =>
                'กรุณาระบุเปอร์เซ็นต์ตามแผน',

            'planned_percent.max' =>
                'เปอร์เซ็นต์ตามแผนต้องไม่เกิน 100',


        ];
    }
}
