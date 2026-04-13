<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'route_code',
    'route_name',
    'starting_point',
    'ending_point',
    'timing',
    'total_distance',
    'district_id',
    'remarks',
])]
class TransportRoute extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'total_distance' => 'integer',
            'district_id' => 'integer',
        ];
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'route_id');
    }

    public function fares(): HasMany
    {
        return $this->hasMany(Fare::class, 'route_id');
    }

    public function tripCosts(): HasMany
    {
        return $this->hasMany(TripCost::class, 'route_id');
    }

    public function challans(): HasMany
    {
        return $this->hasMany(Challan::class, 'route_id');
    }
}
