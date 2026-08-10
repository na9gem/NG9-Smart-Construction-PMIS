<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressPlanItem extends Model
{
    protected $fillable = [
        'progress_plan_id',
        'activity_id',
        'plan_date',
        'planned_percent',
        'planned_weight',
        'cumulative_percent',
    ];

    protected $casts = [
        'plan_date' => 'date',
        'planned_percent' => 'decimal:2',
        'planned_weight' => 'decimal:2',
        'cumulative_percent' => 'decimal:2',
    ];

    public function progressPlan(): BelongsTo
    {
        return $this->belongsTo(ProgressPlan::class);
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }
}