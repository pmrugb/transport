<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transporters', function (Blueprint $table) {
            $table->index('owner_type');
            $table->index('created_at');
        });

        Schema::table('transport_routes', function (Blueprint $table) {
            $table->index('created_at');
        });

        Schema::table('trip_details', function (Blueprint $table) {
            // These columns drive dashboard charts and trip list filters.
            $table->index('status');
            $table->index('trip_date');
            $table->index('created_at');
            $table->index(['department_id', 'trip_date']);
        });

        Schema::table('trip_costs', function (Blueprint $table) {
            // Payment pages filter primarily by status and calculation date.
            $table->index('status');
            $table->index('calculation_date');
            $table->index('created_at');
            $table->index(['status', 'calculation_date']);
        });
    }

    public function down(): void
    {
        Schema::table('trip_costs', function (Blueprint $table) {
            $table->dropIndex(['status', 'calculation_date']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['calculation_date']);
            $table->dropIndex(['status']);
        });

        Schema::table('trip_details', function (Blueprint $table) {
            $table->dropIndex(['department_id', 'trip_date']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['trip_date']);
            $table->dropIndex(['status']);
        });

        Schema::table('transport_routes', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });

        Schema::table('transporters', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['owner_type']);
        });
    }
};
