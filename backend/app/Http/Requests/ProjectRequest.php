<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
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

            'project_code' => [
    'required',
    'string',
    'max:50',
    Rule::unique('projects', 'project_code')
        ->ignore($this->route('project')?->id),
],

            'project_name' => 'required|string|max:255',

            'owner' => 'required|string|max:255',

            'contractor' => 'nullable|string|max:255',

            'consultant' => 'nullable|string|max:255',

            'location' => 'nullable|string',

            'budget' => 'required|numeric|min:0',

            'contract_number' => 'nullable|string|max:100',

            'contract_amount' => 'nullable|numeric|min:0',

            'progress_percent' => 'nullable|numeric|min:0|max:100',

            'planned_start_date' => 'nullable|date',

            'planned_finish_date' => 'nullable|date',

            'actual_finish_date' => 'nullable|date',

            'status' => 'required|string',

            'created_by' => 'nullable|exists:users,id',

        ];
    }

    /**
     * Validation Messages
     */
    public function messages(): array
    {
        return [

            'project_code.required' => 'กรุณาระบุรหัสโครงการ',

            'project_code.unique' => 'รหัสโครงการนี้มีอยู่แล้ว',

            'project_name.required' => 'กรุณาระบุชื่อโครงการ',

            'owner.required' => 'กรุณาระบุหน่วยงานเจ้าของโครงการ',

            'budget.required' => 'กรุณาระบุงบประมาณ',

            'budget.numeric' => 'งบประมาณต้องเป็นตัวเลข',

            'progress_percent.max' => 'ความก้าวหน้าต้องไม่เกิน 100%',

        ];
    }
}