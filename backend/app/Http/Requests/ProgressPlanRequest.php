<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgressPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'contract_id' => [
                'required',
                'exists:contracts,id',
            ],

            'plan_name' => [
                'required',
                'string',
                'max:255',
            ],

            'plan_type' => [
                'required',
                'string',
                'max:100',
            ],

            'version' => [
                'required',
                'string',
                'max:20',
            ],

            'effective_date' => [
                'nullable',
                'date',
            ],

            'source_document_id' => [
                'nullable',
                'exists:documents,id',
            ],

            'is_baseline' => [
                'boolean',
            ],

            'status' => [
                'required',
                'string',
                'max:50',
            ],

            'created_by' => [
                'nullable',
                'exists:users,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [

            'contract_id.required' =>
                'กรุณาเลือกสัญญา',

            'contract_id.exists' =>
                'ไม่พบสัญญาที่เลือก',

            'plan_name.required' =>
                'กรุณาระบุชื่อแผนความก้าวหน้า',

            'plan_type.required' =>
                'กรุณาระบุประเภทแผน',

            'version.required' =>
                'กรุณาระบุ Version',

            'effective_date.date' =>
                'วันที่มีผลของแผนไม่ถูกต้อง',

            'source_document_id.exists' =>
                'ไม่พบเอกสารต้นทาง',

            'created_by.exists' =>
                'ไม่พบผู้สร้างรายการ',
        ];
    }
}
