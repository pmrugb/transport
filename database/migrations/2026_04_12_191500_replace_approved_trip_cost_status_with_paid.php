<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('trip_costs')
            ->where('status', 'approved')
            ->update(['status' => 'paid']);
    }

    public function down(): void
    {
        // Irreversible data cleanup.
    }
};
