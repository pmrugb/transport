<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trip_details', function (Blueprint $table): void {
            $table->string('driver_cnic', 15)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('trip_details', function (Blueprint $table): void {
            $table->string('driver_cnic', 15)->nullable(false)->change();
        });
    }
};
