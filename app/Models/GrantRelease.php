<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'grant_id',
    'release_amount',
    'release_date',
    'installment_no',
    'released_by',
    'remarks',
])]
class GrantRelease extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'grant_id' => 'integer',
            'release_amount' => 'decimal:2',
            'release_date' => 'date',
            'installment_no' => 'integer',
        ];
    }

    public function grant(): BelongsTo
    {
        return $this->belongsTo(Grant::class, 'grant_id');
    }
}
