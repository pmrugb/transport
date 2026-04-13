<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_system')->default(false);
            $table->timestamps();
        });

        $timestamp = Carbon::create(2026, 3, 6, 22, 56, 24);

        DB::table('roles')->insert([
            [
                'id' => 1,
                'name' => 'Super Admin',
                'slug' => 'super_admin',
                'description' => 'Full system control.',
                'is_system' => true,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'id' => 2,
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Administrative access.',
                'is_system' => true,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'id' => 3,
                'name' => 'User',
                'slug' => 'user',
                'description' => 'Standard user access.',
                'is_system' => true,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'id' => 4,
                'name' => 'District Admin',
                'slug' => 'district_admin',
                'description' => 'District-level management.',
                'is_system' => false,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'id' => 5,
                'name' => 'Divisional Admin',
                'slug' => 'divisional_admin',
                'description' => 'Division-level management.',
                'is_system' => false,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
