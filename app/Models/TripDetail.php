<?php

namespace App\Models;

use App\Models\Department;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'trip_date',
    'route_id',
    'vehicle_id',
    'transporter_id',
    'driver_name',
    'driver_cnic',
    'driver_mobile',
    'fare_id',
    'fare_amount',
    'no_of_trips',
    'total_amount',
    'district_id',
    'department_id',
    'status',
    'remarks',
    'created_by',
])]
class TripDetail extends Model
{
    use SoftDeletes;

    public const STATUSES = [
        'active' => 'Active',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ];

    protected function casts(): array
    {
        return [
            'trip_date' => 'date',
            'route_id' => 'integer',
            'vehicle_id' => 'integer',
            'transporter_id' => 'integer',
            'fare_id' => 'integer',
            'fare_amount' => 'decimal:2',
            'no_of_trips' => 'integer',
            'total_amount' => 'decimal:2',
            'district_id' => 'integer',
            'department_id' => 'integer',
            'created_by' => 'integer',
        ];
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(TransportRoute::class, 'route_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function transporter(): BelongsTo
    {
        return $this->belongsTo(Operator::class, 'transporter_id');
    }

    public function fare(): BelongsTo
    {
        return $this->belongsTo(Fare::class, 'fare_id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tripCost(): HasOne
    {
        return $this->hasOne(TripCost::class, 'trip_id');
    }
}
