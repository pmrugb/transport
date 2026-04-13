<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('vehicles', 'chassis_no')) {
            return;
        }

        DB::statement('ALTER TABLE vehicles MODIFY chassis_no VARCHAR(100) NULL');
    }

    public function down(): void
    {
        if (! Schema::hasColumn('vehicles', 'chassis_no')) {
            return;
        }

        DB::statement("UPDATE vehicles SET chassis_no = '' WHERE chassis_no IS NULL");
        DB::statement('ALTER TABLE vehicles MODIFY chassis_no VARCHAR(100) NOT NULL');
    }
};
