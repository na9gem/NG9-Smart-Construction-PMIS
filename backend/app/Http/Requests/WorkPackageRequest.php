<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WorkPackageRequest extends FormRequest
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
            'milestone_id' => [
                'required',
                'integer',
                'exists:milestones,id',
            ],

            'package_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('work_packages', 'package_code')
                    ->where(
                        fn ($query) => $query->where(
                            'milestone_id',
                            $this->input('milestone_id')
                        )
                    )
                     ->ignore($this->route('work_package')),
            ],

            'package_name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'sequence_no' => [
                'required',
                'integer',
                'min:1',
            ],

            'status' => [
                'required',
                'string',
                'in:Draft',
            ],
        ];
    }
}
