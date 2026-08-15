<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MilestoneRequest extends FormRequest
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
            'contract_id' => [
                'required',
                'integer',
                'exists:contracts,id',
            ],

            'milestone_no' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('milestones', 'milestone_no')
                    ->where(
                        fn ($query) => $query->where(
                            'contract_id',
                            $this->input('contract_id')
                        )
                    )
                    ->ignore($this->route('milestone')),
            ],

            'milestone_name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'planned_start_date' => [
                'nullable',
                'date',
            ],

            'planned_finish_date' => [
                'nullable',
                'date',
            ],

            'payment_percent' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'payment_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'status' => [
                'required',
                'string',
                'in:Draft',
            ],
        ];
    }
}
