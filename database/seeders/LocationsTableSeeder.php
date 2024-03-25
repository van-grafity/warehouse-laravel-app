<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'location' => 'Area A',
                'description' => 'Area A',
            ],
            [
                'location' => 'Area B',
                'description' => 'Area B',
            ],
            [
                'location' => 'Area C',
                'description' => 'Area C',
            ],
            [
                'location' => 'Area D',
                'description' => 'Area D',
            ],
            [
                'location' => 'Area E',
                'description' => 'Area E',
            ],
        ];

        foreach ($data as $key => $location) {
            Location::create($location);
        }
    }
}
