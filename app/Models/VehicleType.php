<?php

namespace App\Models;

use App\Models\Concerns\PreservesUniqueFieldsOnSoftDelete;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'name',
    'description',
    'seating_capacity',
    'status',
])]
class VehicleType extends Model
{
    use PreservesUniqueFieldsOnSoftDelete, SoftDeletes;

    public const STATUSES = [
        'active' => 'Active',
        'inactive' => 'Inactive',
    ];

    protected function casts(): array
    {
        return [
            'seating_capacity' => 'integer',
        ];
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'vehicle_type');
    }

    protected function getSoftDeleteUniqueFields(): array
    {
        return ['name'];
    }
}
