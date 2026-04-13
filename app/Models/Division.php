<?php

namespace App\Models;

use App\Models\Concerns\PreservesUniqueFieldsOnSoftDelete;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name'])]
class Division extends Model
{
    use PreservesUniqueFieldsOnSoftDelete, SoftDeletes;

    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }

    protected function getSoftDeleteUniqueFields(): array
    {
        return ['name'];
    }
}
