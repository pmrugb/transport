<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('trip_costs')
            ->whereNotIn('status', ['due', 'approved', 'paid'])
            ->update(['status' => 'due']);
    }

    public function down(): void
    {
        DB::table('trip_costs')
            ->where('status', 'due')
            ->update(['status' => 'active']);
    }
};
