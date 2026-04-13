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
        Schema::create('trip_costs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trip_id')->nullable();
            $table->foreignId('route_id')->constrained('transport_routes')->restrictOnDelete();
            $table->foreignId('vehicle_id')->constrained('vehicles')->restrictOnDelete();
            $table->foreignId('transporter_id')->constrained('transporters')->restrictOnDelete();
            $table->decimal('fare_amount', 10, 2);
            $table->unsignedInteger('no_of_trips');
            $table->decimal('total_amount', 14, 2);
            $table->date('calculation_date')->nullable();
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
        Schema::dropIfExists('trip_costs');
    }
};
