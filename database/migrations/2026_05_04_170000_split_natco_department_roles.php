<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $timestamp = Carbon::now();

        DB::table('roles')
            ->where('slug', 'departmental_admin')
            ->update([
                'name' => 'NATCO Department',
                'description' => 'Department-side NATCO access focused on payments.',
                'sidebar_permissions' => json_encode([
                    'dashboard',
                    'payments',
                    'challans',
                    'settings.profile',
                ], JSON_THROW_ON_ERROR),
                'page_permissions' => json_encode([
                    'payments.view',
                    'payments.manage',
                    'challans.view',
                ], JSON_THROW_ON_ERROR),
                'updated_at' => $timestamp,
            ]);

        $natcoAdminRoleId = DB::table('roles')->where('slug', 'natco_admin')->value('id');

        if (! $natcoAdminRoleId) {
            $natcoAdminRoleId = DB::table('roles')->insertGetId([
                'name' => 'NATCO Admin',
                'slug' => 'natco_admin',
                'description' => 'NATCO admin access focused on trips without payment navigation.',
                'access_scope' => 'department',
                'can_view' => true,
                'can_create' => true,
                'can_edit' => true,
                'can_delete' => false,
                'can_manage_users' => false,
                'can_manage_system_settings' => false,
                'sidebar_permissions' => json_encode([
                    'dashboard',
                    'trips',
                    'challans',
                    'settings.profile',
                ], JSON_THROW_ON_ERROR),
                'page_permissions' => json_encode([
                    'trips.view',
                    'trips.create',
                    'trips.edit',
                    'challans.view',
                ], JSON_THROW_ON_ERROR),
                'is_system' => false,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
        } else {
            DB::table('roles')
                ->where('id', $natcoAdminRoleId)
                ->update([
                    'name' => 'NATCO Admin',
                    'description' => 'NATCO admin access focused on trips without payment navigation.',
                    'access_scope' => 'department',
                    'can_view' => true,
                    'can_create' => true,
                    'can_edit' => true,
                    'can_delete' => false,
                    'can_manage_users' => false,
                    'can_manage_system_settings' => false,
                    'sidebar_permissions' => json_encode([
                        'dashboard',
                        'trips',
                        'challans',
                        'settings.profile',
                    ], JSON_THROW_ON_ERROR),
                    'page_permissions' => json_encode([
                        'trips.view',
                        'trips.create',
                        'trips.edit',
                        'challans.view',
                    ], JSON_THROW_ON_ERROR),
                    'updated_at' => $timestamp,
                ]);
        }

        DB::table('users')
            ->where('email', 'natcoadmin@pmrugb.gov.pk')
            ->update([
                'role' => 'natco_admin',
                'updated_at' => $timestamp,
            ]);
    }

    public function down(): void
    {
        $timestamp = Carbon::now();

        DB::table('users')
            ->where('email', 'natcoadmin@pmrugb.gov.pk')
            ->update([
                'role' => 'departmental_admin',
                'updated_at' => $timestamp,
            ]);

        DB::table('roles')
            ->where('slug', 'natco_admin')
            ->delete();
    }
};
