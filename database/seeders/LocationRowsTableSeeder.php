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
        $rack_code = range('A', 'J');

        foreach ($rack_code as $code) {
            LocationRow::create([
                'row' => $code,
                'description' => 'Row ' . $code,
            ]);
        }
    }
}
