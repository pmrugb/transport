<?php

namespace App\Models;

use App\Models\Concerns\PreservesUniqueFieldsOnSoftDelete;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['division_id', 'name', 'division_name'])]
class District extends Model
{
    use PreservesUniqueFieldsOnSoftDelete, SoftDeletes;

    protected function casts(): array
    {
        return [
            'division_id' => 'integer',
        ];
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function grants(): HasMany
    {
        return $this->hasMany(Grant::class);
    }

    public function challans(): HasMany
    {
        return $this->hasMany(Challan::class);
    }

    protected function getSoftDeleteUniqueFields(): array
    {
        return ['name'];
    }
}
