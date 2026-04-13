<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->string('access_scope', 30)->default('global')->after('description');
            $table->boolean('can_view')->default(true)->after('access_scope');
            $table->boolean('can_create')->default(false)->after('can_view');
            $table->boolean('can_edit')->default(false)->after('can_create');
            $table->boolean('can_delete')->default(false)->after('can_edit');
            $table->boolean('can_manage_users')->default(false)->after('can_delete');
            $table->boolean('can_manage_system_settings')->default(false)->after('can_manage_users');
        });

        DB::table('roles')->where('slug', 'super_admin')->update([
            'access_scope' => 'global',
            'can_view' => true,
            'can_create' => true,
            'can_edit' => true,
            'can_delete' => true,
            'can_manage_users' => true,
            'can_manage_system_settings' => true,
        ]);

        DB::table('roles')->where('slug', 'admin')->update([
            'access_scope' => 'global',
            'can_view' => true,
            'can_create' => true,
            'can_edit' => true,
            'can_delete' => true,
            'can_manage_users' => true,
            'can_manage_system_settings' => true,
        ]);

        DB::table('roles')->where('slug', 'user')->update([
            'access_scope' => 'global',
            'can_view' => true,
        ]);

        DB::table('roles')->where('slug', 'district_admin')->update([
            'access_scope' => 'district',
            'can_view' => true,
            'can_create' => true,
            'can_edit' => true,
            'can_delete' => true,
        ]);

        DB::table('roles')->where('slug', 'divisional_admin')->update([
            'access_scope' => 'division',
            'can_view' => true,
            'can_create' => true,
            'can_edit' => true,
            'can_delete' => true,
        ]);
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn([
                'access_scope',
                'can_view',
                'can_create',
                'can_edit',
                'can_delete',
                'can_manage_users',
                'can_manage_system_settings',
            ]);
        });
    }
};
