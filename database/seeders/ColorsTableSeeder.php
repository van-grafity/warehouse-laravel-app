<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'color' => 'Med Heather Grey',
            ],
            [
                'color' => 'Bleach',
            ],
            [
                'color' => 'Black',
            ],
            [
                'color' => 'Med Soft Pink',
            ],
            [
                'color' => 'Aqua Dream',
            ],
            [
                'color' => 'True Red',
            ],
            [
                'color' => 'Coral',
            ],
            [
                'color' => 'Brown',
            ],
            [
                'color' => 'Blue',
            ],
            [
                'color' => 'Blue Jeans',
            ],
            [
                'color' => 'Burgundy',
            ],
            [
                'color' => 'Navy',
            ],
        ];

        foreach ($data as $key => $color) {
            Color::create($color);
        }
    }
}
