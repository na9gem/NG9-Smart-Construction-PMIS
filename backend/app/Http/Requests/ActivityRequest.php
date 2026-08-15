<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActivityRequest extends FormRequest
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
            'work_package_id' => [
                'required',
                'integer',
                'exists:work_packages,id',
            ],

            'activity_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('activities', 'activity_code')
                    ->where(
                        fn ($query) => $query->where(
                            'work_package_id',
                            $this->input('work_package_id')
                        )
                    )
                    ->ignore($this->route('activity')),
            ],

            'activity_name' => [
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

            'actual_start_date' => [
                'nullable',
                'date',
            ],

            'actual_finish_date' => [
                'nullable',
                'date',
            ],

            'weight' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'status' => [
                'required',
                'string',
                'in:Draft',
            ],
        ];
    }
}
