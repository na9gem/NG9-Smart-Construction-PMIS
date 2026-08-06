<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    /**
     * Mass Assignable
     */
    protected $fillable = [

        'project_id',

        'contract_id',

        'progress_report_id',

        'inspection_id',

        'media_type',

        'file_name',

        'file_path',

        'file_extension',

        'mime_type',

        'file_size',

        'description',

        'ai_summary',

        'uploaded_at',

    ];

    /**
     * Data Casting
     */
    protected $casts = [

        'uploaded_at' => 'datetime',

    ];

    /**
     * Relationships
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function progressReport()
    {
        return $this->belongsTo(ProgressReport::class);
    }


    public function inspection()
    {
        return $this->belongsTo(Inspection::class);
    }
}
