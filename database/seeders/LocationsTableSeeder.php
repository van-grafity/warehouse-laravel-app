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
        $data = [];

        // ## Setting Row 
        // ### ['row_name' => 'total_row']
        $rows = [
            'A' => 35,
            'B' => 8,
            'C' => 8,
            'D' => 8,
            'E' => 17,
            'F' => 17,
            'G' => 17,
            'H' => 17,
            'I' => 17,
            'J' => 17,
        ];

        $row_counter = 1;

        foreach ($rows as $row => $max_columns) {
            for ($i = 1; $i <= $max_columns; $i++) {
                $data[] = [
                    'location' => $row . $i,
                    'description' => 'Row ' . $row . ', Column ' . $i,
                    'location_row_id' => $row_counter,
                ];
            }
            $row_counter++;
        }


        foreach ($data as $key => $location) {
            Location::create($location);
        }
    }
}
