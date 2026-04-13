<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Operator;
use App\Models\TransportRoute;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransportSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::query()->updateOrCreate(
            ['email' => 'admin@pmrugb.test'],
            [
                'name' => 'admin',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'admin',
                'district_id' => 1,
                'division_id' => 1,
            ]
        );

        $operators = collect([
            [
                'owner_type' => 'company',
                'name' => 'Karakoram Express',
                'cnic' => '71101-1234567-1',
                'phone' => '+92 300 1100221',
                'address' => 'Gilgit city terminal office',
                'district_id' => 1,
                'remarks' => 'Main intercity operator for Gilgit to Hunza corridor.',
            ],
            [
                'owner_type' => 'company',
                'name' => 'Northern Transit',
                'cnic' => '71101-1234567-2',
                'phone' => '+92 300 2200345',
                'address' => 'Aliabad main bazaar',
                'district_id' => 7,
                'remarks' => 'Handles high-demand northern sector routes.',
            ],
            [
                'owner_type' => 'private',
                'name' => 'Ali Travel Service',
                'cnic' => '71101-1234567-3',
                'phone' => '+92 300 3100456',
                'address' => 'Skardu transport adda',
                'district_id' => 2,
                'remarks' => 'Pending final approval for seasonal route expansion.',
            ],
            [
                'owner_type' => 'private',
                'name' => 'Mountain Link',
                'cnic' => '71101-1234567-4',
                'phone' => '+92 300 4100789',
                'address' => 'Chilas bus stand',
                'district_id' => 4,
                'remarks' => 'Strong local transport coverage inside Diamer.',
            ],
            [
                'owner_type' => 'company',
                'name' => 'Passu Movers',
                'cnic' => '71101-1234567-5',
                'phone' => '+92 300 5520099',
                'address' => 'Passu main stop',
                'district_id' => 7,
                'remarks' => 'Newly registered operator under operational review.',
            ],
        ])->mapWithKeys(fn (array $operator) => [
            $operator['name'] => Operator::query()->updateOrCreate(
                ['name' => $operator['name']],
                $operator
            ),
        ]);

        $districtIds = District::query()
            ->pluck('id', 'name');

        foreach ([
            [
                'route_name' => 'Gilgit to Hunza',
                'starting_point' => 'Gilgit',
                'ending_point' => 'Hunza',
                'timing' => '6:00 AM to 6:00 PM',
                'total_distance' => 100,
                'district_name' => 'Gilgit',
                'remarks' => 'Six departures daily with steady passenger demand.',
            ],
            [
                'route_name' => 'Skardu to Shigar',
                'starting_point' => 'Skardu',
                'ending_point' => 'Shigar',
                'timing' => '7:00 AM to 5:00 PM',
                'total_distance' => 32,
                'district_name' => 'Skardu',
                'remarks' => 'Popular short-haul route with regular tourist traffic.',
            ],
            [
                'route_name' => 'Gilgit to Astore',
                'starting_point' => 'Gilgit',
                'ending_point' => 'Astore',
                'timing' => '6:30 AM to 4:30 PM',
                'total_distance' => 121,
                'district_name' => 'Astore',
                'remarks' => 'Weekend schedule update is still under review.',
            ],
            [
                'route_name' => 'Diamer Local',
                'starting_point' => 'Chilas',
                'ending_point' => 'Diamer',
                'timing' => '8:00 AM to 8:00 PM',
                'total_distance' => 18,
                'district_name' => 'Diamer',
                'remarks' => 'Urban route supporting local district mobility.',
            ],
            [
                'route_name' => 'Passu Shuttle',
                'starting_point' => 'Passu',
                'ending_point' => 'Sost',
                'timing' => '7:00 AM to 7:00 PM',
                'total_distance' => 45,
                'district_name' => 'Hunza',
                'remarks' => 'Awaiting final corridor clearance.',
            ],
        ] as $route) {
            TransportRoute::query()->updateOrCreate(
                [
                    'route_name' => $route['route_name'],
                    'starting_point' => $route['starting_point'],
                    'ending_point' => $route['ending_point'],
                ],
                [
                    'route_name' => $route['route_name'],
                    'starting_point' => $route['starting_point'],
                    'ending_point' => $route['ending_point'],
                    'timing' => $route['timing'],
                    'total_distance' => $route['total_distance'],
                    'district_id' => $districtIds[$route['district_name']] ?? 1,
                    'remarks' => $route['remarks'],
                ]
            );
        }
    }
}
