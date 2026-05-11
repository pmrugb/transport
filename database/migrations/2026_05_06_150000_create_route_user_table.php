<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('route_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('route_id')->constrained('transport_routes')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'route_id']);
        });

        $now = now();
        $rows = DB::table('users')
            ->whereNotNull('route_id')
            ->select(['id as user_id', 'route_id'])
            ->get()
            ->map(fn ($row): array => [
                'user_id' => $row->user_id,
                'route_id' => $row->route_id,
                'created_at' => $now,
                'updated_at' => $now,
            ])
            ->all();

        if ($rows !== []) {
            DB::table('route_user')->insert($rows);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('route_user');
    }
};
