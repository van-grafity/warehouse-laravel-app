<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rack;

class RacksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'rack' => 'RCK-A001',
                'description' => 'RCK-A001',
            ],
            [
                'rack' => 'RCK-A002',
                'description' => 'RCK-A002',
            ],
            [
                'rack' => 'RCK-A003',
                'description' => 'RCK-A003',
            ],
            [
                'rack' => 'RCK-A004',
                'description' => 'RCK-A004',
            ],
            [
                'rack' => 'RCK-A005',
                'description' => 'RCK-A005',
            ],
        ];

            foreach ($data as $key => $rack) {
            Rack::create($rack);
        }
    }
}
