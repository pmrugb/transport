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
        if (Schema::hasTable('operators') && ! Schema::hasTable('transporters')) {
            Schema::rename('operators', 'transporters');
        }

        if (! Schema::hasTable('transporters')) {
            Schema::create('transporters', function (Blueprint $table) {
                $table->id();
                $table->string('owner_type', 30);
                $table->string('name');
                $table->string('cnic', 30)->nullable()->unique();
                $table->string('phone', 50);
                $table->string('address')->nullable();
                $table->foreignId('district_id')->nullable()->constrained('districts')->nullOnDelete();
                $table->text('remarks')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('transporters', function (Blueprint $table) {
                if (! Schema::hasColumn('transporters', 'owner_type')) {
                    $table->string('owner_type', 30)->nullable()->after('id');
                }
                if (! Schema::hasColumn('transporters', 'cnic')) {
                    $table->string('cnic', 30)->nullable()->after('name');
                }
                if (! Schema::hasColumn('transporters', 'address')) {
                    $table->string('address')->nullable()->after('phone');
                }
                if (! Schema::hasColumn('transporters', 'district_id')) {
                    $table->foreignId('district_id')->nullable()->after('address')->constrained('districts')->nullOnDelete();
                }
                if (! Schema::hasColumn('transporters', 'remarks')) {
                    $table->text('remarks')->nullable()->after('district_id');
                }
            });

            DB::table('transporters')->update([
                'owner_type' => DB::raw('type'),
                'remarks' => DB::raw('notes'),
            ]);

            $districts = DB::table('districts')->pluck('id', 'name');
            $transporters = DB::table('transporters')->select('id', 'district')->get();

            foreach ($transporters as $transporter) {
                DB::table('transporters')
                    ->where('id', $transporter->id)
                    ->update([
                        'district_id' => $districts[$transporter->district] ?? null,
                    ]);
            }

            Schema::table('transporters', function (Blueprint $table) {
                if (Schema::hasColumn('transporters', 'type')) {
                    $table->dropColumn(['type', 'owner_name', 'district', 'vehicles', 'status', 'notes']);
                }
            });

            if (! $this->hasUniqueIndex('transporters', 'transporters_cnic_unique')) {
                Schema::table('transporters', function (Blueprint $table) {
                    $table->unique('cnic');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('transporters')) {
            return;
        }

        Schema::table('transporters', function (Blueprint $table) {
            if (! Schema::hasColumn('transporters', 'type')) {
                $table->string('type', 30)->nullable()->after('id');
                $table->string('owner_name')->nullable()->after('name');
                $table->string('district', 100)->nullable()->after('phone');
                $table->unsignedInteger('vehicles')->default(1)->after('district');
                $table->string('status', 30)->default('pending')->after('vehicles');
                $table->text('notes')->nullable()->after('status');
            }
        });

        DB::table('transporters')->update([
            'type' => DB::raw('owner_type'),
            'notes' => DB::raw('remarks'),
        ]);

        $districts = DB::table('districts')->pluck('name', 'id');
        $transporters = DB::table('transporters')->select('id', 'district_id')->get();

        foreach ($transporters as $transporter) {
            DB::table('transporters')
                ->where('id', $transporter->id)
                ->update([
                    'district' => $districts[$transporter->district_id] ?? null,
                ]);
        }

        if ($this->hasUniqueIndex('transporters', 'transporters_cnic_unique')) {
            Schema::table('transporters', function (Blueprint $table) {
                $table->dropUnique('transporters_cnic_unique');
            });
        }

        Schema::table('transporters', function (Blueprint $table) {
            if (Schema::hasColumn('transporters', 'district_id')) {
                $table->dropForeign(['district_id']);
            }
            $table->dropColumn(['owner_type', 'cnic', 'address', 'district_id', 'remarks']);
        });

        if (! Schema::hasTable('operators')) {
            Schema::rename('transporters', 'operators');
        }
    }

    private function hasUniqueIndex(string $table, string $index): bool
    {
        $connection = Schema::getConnection();

        if ($connection->getDriverName() === 'sqlite') {
            return false;
        }

        $databaseName = $connection->getDatabaseName();

        return DB::table('information_schema.statistics')
            ->where('table_schema', $databaseName)
            ->where('table_name', $table)
            ->where('index_name', $index)
            ->exists();
    }
};
