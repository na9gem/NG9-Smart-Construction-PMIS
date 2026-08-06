<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgressReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation Rules
     */
    public function rules(): array
    {
        return [

            'project_id' => 'required|exists:projects,id',

            'contract_id' => 'nullable|exists:contracts,id',

            'report_date' => 'required|date',

            'progress_percent' => 'required|numeric|min:0|max:100',

            'work_description' => 'required|string',

            'problem' => 'nullable|string',

            'solution' => 'nullable|string',

            'weather' => 'nullable|string|max:100',

            'manpower' => 'nullable|integer|min:0',

            'status' => 'nullable|in:Draft,Submitted,Approved',

        ];
    }

    /**
     * Validation Messages
     */
    public function messages(): array
    {
        return [

            'project_id.required' => 'กรุณาเลือกโครงการ',

            'project_id.exists' => 'ไม่พบโครงการที่เลือก',

            'report_date.required' => 'กรุณาระบุวันที่รายงาน',

            'progress_percent.required' => 'กรุณาระบุเปอร์เซ็นต์ความก้าวหน้า',

            'progress_percent.max' => 'เปอร์เซ็นต์ต้องไม่เกิน 100',

            'work_description.required' => 'กรุณาระบุรายละเอียดงาน',

        ];
    }
}
