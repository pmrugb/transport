<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'trip_id',
    'route_id',
    'vehicle_id',
    'transporter_id',
    'fare_amount',
    'no_of_trips',
    'total_amount',
    'calculation_date',
    'status',
    'remarks',
])]
class TripCost extends Model
{
    use SoftDeletes;

    public const STATUSES = [
        'due' => 'Due Payment',
        'paid' => 'Paid',
        'rejected' => 'Rejected',
    ];

    protected function casts(): array
    {
        return [
            'trip_id' => 'integer',
            'route_id' => 'integer',
            'vehicle_id' => 'integer',
            'transporter_id' => 'integer',
            'fare_amount' => 'decimal:2',
            'no_of_trips' => 'integer',
            'total_amount' => 'decimal:2',
            'calculation_date' => 'date',
        ];
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(TransportRoute::class, 'route_id');
    }

    public function trip(): BelongsTo
    {
        return $this->belongsTo(TripDetail::class, 'trip_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function transporter(): BelongsTo
    {
        return $this->belongsTo(Operator::class, 'transporter_id');
    }
}
