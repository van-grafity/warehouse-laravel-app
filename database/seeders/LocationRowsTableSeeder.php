<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LocationRow;

class LocationRowsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'row' => 'A',
                'description' => 'Row A',
            ],
            [
                'row' => 'B',
                'description' => 'Row B',
            ],
            [
                'row' => 'C',
                'description' => 'Row C',
            ],
            [
                'row' => 'D',
                'description' => 'Row D',
            ],
            [
                'row' => 'E',
                'description' => 'Row E',
            ],
            [
                'row' => 'F',
                'description' => 'Row F',
            ],
            [
                'row' => 'G',
                'description' => 'Row G',
            ],
            [
                'row' => 'H',
                'description' => 'Row H',
            ],
            [
                'row' => 'I',
                'description' => 'Row I',
            ],
            [
                'row' => 'J',
                'description' => 'Row J',
            ],
        ];

        foreach ($data as $key => $location) {
            LocationRow::create($location);
        }
    }
}
