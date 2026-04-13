<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['id' => 1, 'division_id' => 1, 'name' => 'Gilgit', 'division_name' => 'Gilgit'],
            ['id' => 2, 'division_id' => 2, 'name' => 'Skardu', 'division_name' => 'Baltistan'],
            ['id' => 3, 'division_id' => 2, 'name' => 'Shigar', 'division_name' => 'Baltistan'],
            ['id' => 4, 'division_id' => 3, 'name' => 'Diamer', 'division_name' => 'Diamer-Astore'],
            ['id' => 5, 'division_id' => 3, 'name' => 'Astore', 'division_name' => 'Diamer-Astore'],
            ['id' => 6, 'division_id' => 2, 'name' => 'Ghanche', 'division_name' => 'Baltistan'],
            ['id' => 7, 'division_id' => 1, 'name' => 'Hunza', 'division_name' => 'Gilgit'],
            ['id' => 8, 'division_id' => 1, 'name' => 'Nagar', 'division_name' => 'Gilgit'],
            ['id' => 9, 'division_id' => 2, 'name' => 'Kharmang', 'division_name' => 'Baltistan'],
            ['id' => 10, 'division_id' => 1, 'name' => 'Ghizer', 'division_name' => 'Gilgit'],
        ] as $district) {
            District::withTrashed()->updateOrCreate(
                ['id' => $district['id']],
                [
                    'division_id' => $district['division_id'],
                    'name' => $district['name'],
                    'division_name' => $district['division_name'],
                    'deleted_at' => null,
                ]
            );
        }
    }
}
