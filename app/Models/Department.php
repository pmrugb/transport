<?php

namespace App\Models;

use App\Models\Concerns\PreservesUniqueFieldsOnSoftDelete;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

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

    public static function natcoId(): ?int
    {
        return Cache::remember('departments:natco-id', now()->addMinutes(10), function (): ?int {
            return static::query()
                ->whereRaw('LOWER(name) = ?', ['natco'])
                ->value('id');
        });
    }

    protected function getSoftDeleteUniqueFields(): array
    {
        return ['name'];
    }
}
