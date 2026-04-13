<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('transport_routes') || ! Schema::hasColumn('transport_routes', 'route_code')) {
            return;
        }

        Schema::table('transport_routes', function (Blueprint $table) {
            $table->dropColumn('route_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('transport_routes') || Schema::hasColumn('transport_routes', 'route_code')) {
            return;
        }

        Schema::table('transport_routes', function (Blueprint $table) {
            $table->string('route_code', 50)->nullable()->after('id');
        });
    }
};
