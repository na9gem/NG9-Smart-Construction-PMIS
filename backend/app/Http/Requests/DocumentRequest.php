<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
{
    return [

        'project_id' => 'required|exists:projects,id',

        'contract_id' => 'nullable|exists:contracts,id',

        'document_type' => 'required|string|max:100',

        'document_no' => 'nullable|string|max:100',

        'document_name' => 'required|string|max:255',

        'document_date' => 'nullable|date',

'document_file' => [
    $this->isMethod('post') ? 'required' : 'nullable',
    'file',
    'max:51200',
    'mimes:pdf,jpg,jpeg,png,xlsx,xls,doc,docx',
],
        'revision' => 'nullable|string|max:20',

        'remark' => 'nullable|string',

        'uploaded_by' => 'nullable|exists:users,id',

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

        'document_type.required' => 'กรุณาเลือกประเภทเอกสาร',

        'document_name.required' => 'กรุณาระบุชื่อเอกสาร',

        'document_file.required' => 'กรุณาเลือกไฟล์',

        'document_file.file' => 'ไฟล์ไม่ถูกต้อง',

        'document_file.mimes' => 'รองรับเฉพาะ PDF, JPG, PNG, Word และ Excel',

        'document_file.max' => 'ไฟล์ต้องมีขนาดไม่เกิน 50 MB',
    ];
}


}
