<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressReport extends Model
{
    use HasFactory;

    /**
     * Mass Assignable
     */
    protected $fillable = [

        'project_id',

        'contract_id',

        'report_date',

        'progress_percent',

        'work_description',

        'problem',

        'solution',

        'weather',

        'manpower',

        'status',

    ];

    /**
     * Data Type Casting
     */
    protected $casts = [

        'report_date' => 'date',

        'progress_percent' => 'decimal:2',

        'manpower' => 'integer',

    ];

    /**
     * Relationship : Progress belongs to Project
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Relationship : Progress belongs to Contract
     */
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
