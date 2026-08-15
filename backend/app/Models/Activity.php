<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_package_id',
        'activity_code',
        'activity_name',
        'description',
        'planned_start_date',
        'planned_finish_date',
        'actual_start_date',
        'actual_finish_date',
        'weight',
        'status',
    ];

    protected $casts = [
        'planned_start_date' => 'date',
        'planned_finish_date' => 'date',
        'actual_start_date' => 'date',
        'actual_finish_date' => 'date',
        'weight' => 'decimal:2',
    ];

    public function workPackage(): BelongsTo
    {
        return $this->belongsTo(WorkPackage::class);
    }
}
