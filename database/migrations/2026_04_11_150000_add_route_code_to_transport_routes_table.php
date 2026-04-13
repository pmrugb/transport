<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @var array<string, string>
     */
    private array $districtCodes = [
        'Gilgit' => 'GLT',
        'Skardu' => 'SKD',
        'Hunza' => 'HNZ',
        'Nagar' => 'NGR',
        'Ghizer' => 'GZR',
        'Diamer' => 'DMR',
        'Ghanche' => 'GHC',
        'Kharmang' => 'KMG',
        'Astore' => 'AST',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('transport_routes') || Schema::hasColumn('transport_routes', 'route_code')) {
            return;
        }

        Schema::table('transport_routes', function (Blueprint $table) {
            $table->string('route_code', 20)->nullable()->after('id');
        });

        $routes = DB::table('transport_routes')
            ->join('districts', 'districts.id', '=', 'transport_routes.district_id')
            ->select('transport_routes.id', 'districts.name as district_name')
            ->orderBy('transport_routes.id')
            ->get();

        $counters = [];

        foreach ($routes as $route) {
            $prefix = $this->districtCodes[$route->district_name] ?? strtoupper(substr($route->district_name, 0, 3));
            $sequence = ($counters[$prefix] ?? 0) + 1;
            $counters[$prefix] = $sequence;

            DB::table('transport_routes')
                ->where('id', $route->id)
                ->update([
                    'route_code' => sprintf('%s-RT-%02d', $prefix, $sequence),
                ]);
        }

        Schema::table('transport_routes', function (Blueprint $table) {
            $table->unique('route_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('transport_routes') || ! Schema::hasColumn('transport_routes', 'route_code')) {
            return;
        }

        Schema::table('transport_routes', function (Blueprint $table) {
            $table->dropUnique(['route_code']);
            $table->dropColumn('route_code');
        });
    }
};
