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
        Schema::create('grant_releases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grant_id')->constrained('grants')->cascadeOnDelete();
            $table->decimal('release_amount', 14, 2);
            $table->date('release_date')->nullable();
            $table->unsignedInteger('installment_no');
            $table->string('released_by')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grant_releases');
    }
};
