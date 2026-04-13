<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach ([
            'users',
            'departments',
            'roles',
            'transporters',
            'transport_routes',
            'vehicle_types',
            'vehicles',
            'fares',
            'grants',
            'grant_releases',
            'trip_details',
            'trip_costs',
            'challans',
        ] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        foreach ([
            'challans',
            'trip_costs',
            'trip_details',
            'grant_releases',
            'grants',
            'fares',
            'vehicles',
            'vehicle_types',
            'transport_routes',
            'transporters',
            'roles',
            'departments',
            'users',
        ] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
