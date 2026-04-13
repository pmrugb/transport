<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('vehicles') || ! Schema::hasTable('transporters')) {
            return;
        }

        Schema::table('vehicles', function (Blueprint $table) {
            if ($this->hasForeignKey('vehicles', 'vehicles_transporter_id_foreign')) {
                $table->dropForeign('vehicles_transporter_id_foreign');
            }

            $table->foreign('transporter_id')->references('id')->on('transporters')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('vehicles') || ! Schema::hasTable('operators')) {
            return;
        }

        Schema::table('vehicles', function (Blueprint $table) {
            if ($this->hasForeignKey('vehicles', 'vehicles_transporter_id_foreign')) {
                $table->dropForeign('vehicles_transporter_id_foreign');
            }

            $table->foreign('transporter_id')->references('id')->on('operators')->cascadeOnDelete();
        });
    }

    private function hasForeignKey(string $table, string $constraint): bool
    {
        $connection = Schema::getConnection();

        if ($connection->getDriverName() === 'sqlite') {
            return false;
        }

        $databaseName = $connection->getDatabaseName();

        return DB::table('information_schema.table_constraints')
            ->where('constraint_schema', $databaseName)
            ->where('table_name', $table)
            ->where('constraint_name', $constraint)
            ->where('constraint_type', 'FOREIGN KEY')
            ->exists();
    }
};
