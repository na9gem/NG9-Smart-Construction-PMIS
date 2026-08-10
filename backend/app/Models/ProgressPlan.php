<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgressPlan extends Model
{
    protected $fillable = [
        'contract_id',
        'plan_name',
        'plan_type',
        'version',
        'effective_date',
        'source_document_id',
        'is_baseline',
        'status',
        'created_by',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'is_baseline' => 'boolean',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function sourceDocument(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'source_document_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ProgressPlanItem::class);
    }
}