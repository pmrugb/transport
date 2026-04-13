<?php

namespace App\Models;

use App\Models\Concerns\PreservesUniqueFieldsOnSoftDelete;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'name',
    'status',
])]
class Department extends Model
{
    use PreservesUniqueFieldsOnSoftDelete, SoftDeletes;

    public const STATUSES = [
        'active' => 'Active',
        'inactive' => 'Inactive',
    ];

    protected function getSoftDeleteUniqueFields(): array
    {
        return ['name'];
    }
}
