<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    use HasFactory;

    /**
     * Mass Assignable
     */
    protected $fillable = [

        'project_id',

        'contract_id',

        'inspection_date',

        'inspection_type',

        'location',

        'result',

        'remark',

        'corrective_action',

        'due_date',

        'status',

    ];

    /**
     * Data Type Casting
     */
    protected $casts = [

        'inspection_date' => 'date',

        'due_date' => 'date',

    ];

    /**
     * Relationship : Inspection belongs to Project
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Relationship : Inspection belongs to Contract
     */
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
