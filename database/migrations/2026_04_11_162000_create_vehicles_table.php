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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transporter_id')->constrained('operators')->cascadeOnDelete();
            $table->foreignId('vehicle_type')->constrained('vehicle_types')->restrictOnDelete();
            $table->string('registration_no')->unique();
            $table->string('chassis_no')->unique();
            $table->foreignId('route_id')->constrained('transport_routes')->restrictOnDelete();
            $table->string('status', 30)->default('active');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
