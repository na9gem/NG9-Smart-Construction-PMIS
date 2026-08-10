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

            'progress_plan_id' => [
                'required',
                'exists:progress_plans,id',
            ],

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

            'planned_weight' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'cumulative_percent' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],
        ];
    }

    public function messages(): array
    {
        return [

            'progress_plan_id.required' =>
                'กรุณาเลือก Progress Plan',

            'progress_plan_id.exists' =>
                'ไม่พบ Progress Plan',

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

            'planned_weight.required' =>
                'กรุณาระบุน้ำหนักงาน',

            'planned_weight.max' =>
                'น้ำหนักงานต้องไม่เกิน 100',
        ];
    }
}
