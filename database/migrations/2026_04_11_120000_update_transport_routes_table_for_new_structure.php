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
        if (! Schema::hasTable('transport_routes')) {
            Schema::create('transport_routes', function (Blueprint $table) {
                $table->id();
                $table->string('route_name');
                $table->string('starting_point');
                $table->string('ending_point');
                $table->unsignedInteger('total_distance');
                $table->foreignId('district_id')->constrained('districts')->restrictOnDelete();
                $table->text('remarks')->nullable();
                $table->timestamps();
            });

            return;
        }

        if (
            Schema::hasColumn('transport_routes', 'starting_point')
            && Schema::hasColumn('transport_routes', 'ending_point')
            && Schema::hasColumn('transport_routes', 'total_distance')
            && Schema::hasColumn('transport_routes', 'district_id')
            && Schema::hasColumn('transport_routes', 'remarks')
            && ! Schema::hasColumn('transport_routes', 'route_code')
        ) {
            return;
        }

        Schema::table('transport_routes', function (Blueprint $table) {
            $table->string('starting_point')->nullable()->after('route_name');
            $table->string('ending_point')->nullable()->after('starting_point');
            $table->unsignedInteger('total_distance')->nullable()->after('ending_point');
            $table->foreignId('district_id')->nullable()->after('total_distance')->constrained('districts')->restrictOnDelete();
            $table->text('remarks')->nullable()->after('district_id');
        });

        DB::table('transport_routes')->update([
            'starting_point' => DB::raw('start_location'),
            'ending_point' => DB::raw('end_location'),
            'total_distance' => DB::raw('distance'),
            'remarks' => DB::raw('notes'),
        ]);

        $defaultDistrictId = DB::table('districts')->orderBy('id')->value('id');

        if ($defaultDistrictId !== null) {
            DB::table('transport_routes')
                ->whereNull('district_id')
                ->update(['district_id' => $defaultDistrictId]);
        }

        Schema::table('transport_routes', function (Blueprint $table) {
            $table->dropForeign(['operator_id']);
            $table->dropColumn([
                'operator_id',
                'operator_type',
                'route_code',
                'start_location',
                'end_location',
                'fare',
                'distance',
                'status',
                'notes',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('transport_routes')) {
            return;
        }

        Schema::table('transport_routes', function (Blueprint $table) {
            $table->foreignId('operator_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            $table->string('operator_type', 30)->nullable()->after('operator_id');
            $table->string('route_code', 50)->nullable()->after('route_name');
            $table->string('start_location')->nullable()->after('route_code');
            $table->string('end_location')->nullable()->after('start_location');
            $table->decimal('fare', 10, 2)->default(0)->after('end_location');
            $table->unsignedInteger('distance')->nullable()->after('fare');
            $table->string('status', 30)->default('pending')->after('distance');
            $table->text('notes')->nullable()->after('status');
        });

        DB::table('transport_routes')->update([
            'start_location' => DB::raw('starting_point'),
            'end_location' => DB::raw('ending_point'),
            'distance' => DB::raw('total_distance'),
            'notes' => DB::raw('remarks'),
        ]);

        $defaultOperatorId = DB::table('operators')->orderBy('id')->value('id');

        if ($defaultOperatorId !== null) {
            DB::table('transport_routes')
                ->whereNull('operator_id')
                ->update([
                    'operator_id' => $defaultOperatorId,
                    'operator_type' => DB::table('operators')->where('id', $defaultOperatorId)->value('type') ?? 'company',
                ]);
        }

        Schema::table('transport_routes', function (Blueprint $table) {
            $table->dropForeign(['district_id']);
            $table->dropColumn([
                'starting_point',
                'ending_point',
                'total_distance',
                'district_id',
                'remarks',
            ]);
        });
    }
};
