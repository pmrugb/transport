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
        if (! Schema::hasTable('transport_routes') || Schema::hasColumn('transport_routes', 'timing')) {
            return;
        }

        Schema::table('transport_routes', function (Blueprint $table) {
            $table->string('timing')->nullable()->after('ending_point');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('transport_routes') || ! Schema::hasColumn('transport_routes', 'timing')) {
            return;
        }

        Schema::table('transport_routes', function (Blueprint $table) {
            $table->dropColumn('timing');
        });
    }
};
