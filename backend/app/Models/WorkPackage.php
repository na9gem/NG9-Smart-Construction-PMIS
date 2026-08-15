<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'milestone_id',
        'package_code',
        'package_name',
        'description',
        'sequence_no',
        'status',
    ];

    protected $casts = [
        'sequence_no' => 'integer',
    ];

    public function milestone(): BelongsTo
    {
        return $this->belongsTo(Milestone::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }
}
