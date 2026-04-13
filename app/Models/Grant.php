<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'title',
    'total_amount',
    'financial_year',
    'district_id',
    'start_date',
    'end_date',
    'status',
    'remarks',
])]
class Grant extends Model
{
    use SoftDeletes;

    public const STATUSES = [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'completed' => 'Completed',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'district_id' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function releases(): HasMany
    {
        return $this->hasMany(GrantRelease::class, 'grant_id');
    }
}
