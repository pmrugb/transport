<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'challan_date',
    'route_id',
    'starting_point',
    'ending_point',
    'district_id',
    'challan_image',
    'remarks',
])]
class Challan extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'challan_date' => 'date',
            'route_id' => 'integer',
            'district_id' => 'integer',
        ];
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(TransportRoute::class, 'route_id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}
