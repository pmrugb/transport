<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            1 => 'Gilgit',
            2 => 'Baltistan',
            3 => 'Diamer-Astore',
        ] as $id => $name) {
            Division::withTrashed()->updateOrCreate(
                ['id' => $id],
                [
                    'name' => $name,
                    'deleted_at' => null,
                ]
            );
        }
    }
}
