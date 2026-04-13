<?php

namespace App\Models;

use App\Models\Concerns\PreservesUniqueFieldsOnSoftDelete;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'transporter_id',
    'vehicle_type',
    'registration_no',
    'chassis_no',
    'route_id',
    'status',
    'remarks',
])]
class Vehicle extends Model
{
    use PreservesUniqueFieldsOnSoftDelete, SoftDeletes;

    public const STATUSES = [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'maintenance' => 'Maintenance',
    ];

    protected function casts(): array
    {
        return [
            'transporter_id' => 'integer',
            'vehicle_type' => 'integer',
            'route_id' => 'integer',
        ];
    }

    public function transporter(): BelongsTo
    {
        return $this->belongsTo(Operator::class, 'transporter_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type');
    }

    public function vehicleType(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type');
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(TransportRoute::class, 'route_id');
    }

    public function tripCosts(): HasMany
    {
        return $this->hasMany(TripCost::class, 'vehicle_id');
    }

    protected function getSoftDeleteUniqueFields(): array
    {
        return ['registration_no', 'chassis_no'];
    }
}
