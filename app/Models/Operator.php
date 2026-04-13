<?php

namespace App\Models;

use App\Models\Concerns\PreservesUniqueFieldsOnSoftDelete;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'owner_type',
    'name',
    'cnic',
    'phone',
    'address',
    'easypaisa_no',
    'jazzcash_no',
    'bank_name',
    'bank_account_title',
    'bank_account_no',
    'district_id',
    'remarks',
])]
class Operator extends Model
{
    use PreservesUniqueFieldsOnSoftDelete, SoftDeletes;

    protected $table = 'transporters';

    public const OWNER_TYPES = [
        'company' => 'Transport Company',
        'private' => 'Private Operator',
    ];

    protected function casts(): array
    {
        return [
            'district_id' => 'integer',
        ];
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'transporter_id');
    }

    public function tripCosts(): HasMany
    {
        return $this->hasMany(TripCost::class, 'transporter_id');
    }

    protected function getSoftDeleteUniqueFields(): array
    {
        return ['cnic'];
    }
}
