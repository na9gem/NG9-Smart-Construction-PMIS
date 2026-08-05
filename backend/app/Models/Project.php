<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    /**
     * Mass Assignable
     */
    protected $fillable = [

        'project_code',
        'project_name',

        'owner',
        'contractor',
        'consultant',

        'location',

        'budget',

        'contract_number',
        'contract_amount',

        'progress_percent',

        'planned_start_date',
        'planned_finish_date',

        'actual_finish_date',

        'status',

        'created_by',
    ];

    /**
     * Data Type Casting
     */
    protected $casts = [

        'budget' => 'decimal:2',
        'contract_amount' => 'decimal:2',
        'progress_percent' => 'decimal:2',

        'planned_start_date' => 'date',
        'planned_finish_date' => 'date',
        'actual_finish_date' => 'date',
    ];

    /**
     * Relationships
     */

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
