<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    /**
     * Mass Assignable
    */
    protected $fillable = [

    'project_id',

    'contract_no',
    'contract_date',

    'contract_amount',

    'contract_days',

    'start_date',
    'finish_date',

    'extended_finish_date',

    'performance_bond',

    'retention_percent',

    'penalty_per_day',

    'procurement_method',

    'status',

];

/**
 * Data Type Casting
 */
protected $casts = [

    'contract_amount' => 'decimal:2',

    'performance_bond' => 'decimal:2',

    'retention_percent' => 'decimal:2',

    'penalty_per_day' => 'decimal:2',

    'contract_date' => 'date',

    'start_date' => 'date',

    'finish_date' => 'date',

    'extended_finish_date' => 'date',

 ];

 /**
 * Relationship : Contract belongs to Project
 */
public function project()
{
    return $this->belongsTo(Project::class);
}

}
