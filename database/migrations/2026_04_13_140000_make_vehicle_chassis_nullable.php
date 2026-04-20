<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('vehicles', 'chassis_no')) {
            return;
        }

        Schema::table('vehicles', function (Blueprint $table): void {
            $table->string('chassis_no', 100)->nullable()->change();
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('vehicles', 'chassis_no')) {
            return;
        }

        DB::statement("UPDATE vehicles SET chassis_no = '' WHERE chassis_no IS NULL");

        Schema::table('vehicles', function (Blueprint $table): void {
            $table->string('chassis_no', 100)->nullable(false)->change();
        });
    }
};
