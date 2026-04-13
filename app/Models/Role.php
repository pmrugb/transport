<?php

namespace App\Models;

use App\Models\Concerns\PreservesUniqueFieldsOnSoftDelete;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'name',
    'slug',
    'description',
    'access_scope',
    'can_view',
    'can_create',
    'can_edit',
    'can_delete',
    'can_manage_users',
    'can_manage_system_settings',
    'is_system',
])]
class Role extends Model
{
    use PreservesUniqueFieldsOnSoftDelete, SoftDeletes;

    public const ACCESS_SCOPES = [
        'global' => 'Global',
        'department' => 'Department',
        'district' => 'District',
        'division' => 'Division',
    ];

    protected function casts(): array
    {
        return [
            'can_view' => 'boolean',
            'can_create' => 'boolean',
            'can_edit' => 'boolean',
            'can_delete' => 'boolean',
            'can_manage_users' => 'boolean',
            'can_manage_system_settings' => 'boolean',
            'is_system' => 'boolean',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'role', 'slug');
    }

    protected function getSoftDeleteUniqueFields(): array
    {
        return ['name', 'slug'];
    }
}
