<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Milestone extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'milestone_no',
        'milestone_name',
        'description',
        'planned_start_date',
        'planned_finish_date',
        'payment_percent',
        'payment_amount',
        'status',
    ];

    protected $casts = [
        'planned_start_date' => 'date',
        'planned_finish_date' => 'date',
        'payment_percent' => 'decimal:2',
        'payment_amount' => 'decimal:2',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function workPackages(): HasMany
    {
        return $this->hasMany(WorkPackage::class);
    }
}
