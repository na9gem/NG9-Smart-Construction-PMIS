<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MediaRequest extends FormRequest
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

            'progress_report_id' => 'nullable|exists:progress_reports,id',

            'inspection_id' => 'nullable|exists:inspections,id',

            'media_type' => 'required|in:Photo,Document,Drawing,Video,Other',

            'media_file' => 'required|file|max:20480',

            'description' => 'nullable|string',

        ];
    }
}
