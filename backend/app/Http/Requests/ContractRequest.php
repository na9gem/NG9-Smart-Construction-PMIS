<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContractRequest extends FormRequest
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
            'project_id' => [
                'required',
                'exists:projects,id',
                Rule::unique('contracts', 'project_id')
                    ->ignore($this->route('contract')),
            ],

            'contract_no' => 'required|string|max:100',

            'contract_date' => 'required|date',

            'contract_amount' => 'required|numeric|min:0',
            'contract_days' => 'required|integer|min:1',

            'start_date' => 'required|date',

            'finish_date' => 'required|date',

            'performance_bond' => 'nullable|numeric|min:0',

            'retention_percent' => 'nullable|numeric|min:0|max:100',

            'penalty_per_day' => 'nullable|numeric|min:0',

            'extended_finish_date' => 'nullable|date',

            'procurement_method' => 'nullable|string|max:100',

            'status' => 'required|string',
        ];
    }
}