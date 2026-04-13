<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('challans', function (Blueprint $table) {
            $table->id();
            $table->date('challan_date');
            $table->foreignId('route_id')->nullable()->constrained('transport_routes')->nullOnDelete();
            $table->string('starting_point');
            $table->string('ending_point');
            $table->foreignId('district_id')->nullable()->constrained()->nullOnDelete();
            $table->string('challan_image')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('challans');
    }
};
