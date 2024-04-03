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
                'location' => 'A1',
                'description' => 'Row A, Column 1',
                'location_row_id' => '1',
            ],
            [
                'location' => 'B1',
                'description' => 'Row B, Column 1',
                'location_row_id' => '2',
            ],
            [
                'location' => 'C1',
                'description' => 'Row C, Column 1',
                'location_row_id' => '3',
            ],
            [
                'location' => 'D1',
                'description' => 'Row D, Column 1',
                'location_row_id' => '4',
            ],
            [
                'location' => 'E1',
                'description' => 'Row E, Column 1',
                'location_row_id' => '5',
            ],
        ];

        foreach ($data as $key => $location) {
            Location::create($location);
        }
    }
}
