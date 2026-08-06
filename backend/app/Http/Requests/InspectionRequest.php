<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InspectionRequest extends FormRequest
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

            'inspection_date' => 'required|date',

            'inspection_type' => 'required|in:Quality,Safety,Material,Progress',

            'location' => 'nullable|string|max:255',

            'result' => 'required|in:Pass,Fail',

            'remark' => 'nullable|string',

            'corrective_action' => 'nullable|string',

            'due_date' => 'nullable|date',

            'status' => 'nullable|in:Open,Closed',

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

            'inspection_date.required' => 'กรุณาระบุวันที่ตรวจ',

            'inspection_type.required' => 'กรุณาเลือกประเภทการตรวจ',

            'result.required' => 'กรุณาระบุผลการตรวจ',

            'result.in' => 'ผลการตรวจต้องเป็น Pass หรือ Fail',

        ];
    }
}
