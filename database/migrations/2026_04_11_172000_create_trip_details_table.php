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
        Schema::create('trip_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained('transport_routes')->restrictOnDelete();
            $table->foreignId('vehicle_id')->constrained('vehicles')->restrictOnDelete();
            $table->foreignId('transporter_id')->constrained('transporters')->restrictOnDelete();
            $table->string('driver_name');
            $table->string('driver_cnic', 15);
            $table->string('driver_mobile', 20);
            $table->foreignId('fare_id')->constrained('fares')->restrictOnDelete();
            $table->decimal('fare_amount', 10, 2);
            $table->unsignedInteger('no_of_trips')->default(1);
            $table->date('trip_date');
            $table->decimal('total_amount', 14, 2);
            $table->foreignId('district_id')->constrained('districts')->restrictOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('grant_id')->nullable()->constrained('grants')->nullOnDelete();
            $table->string('status', 30)->default('active');
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_details');
    }
};
