<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\Rack;

class RacksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ## Rack Setup
        $rack_code = ['MV', 'FX']; // ## MV = moveable, FX = fixed
        $rack_type = ['moveable', 'fixed'];
        $rack_total = [350,72]; // ## [{movable}, {fixed}]
        $racks_data = [];
        $timestamps = Carbon::now();

        foreach ($rack_code as $key_code => $code) {
            $rack_total_this_code = $rack_total[$key_code];
            for ($i=0; $i < $rack_total_this_code; $i++) { 
                $basic_number = $i+1;
                $normalize_number = str_pad($basic_number, 3, '0', STR_PAD_LEFT); // ## running number for each rack type
                $serial_number = 'WHA-RCK-'. $code . '-' . $normalize_number;
                $rack = [
                    'serial_number' => $serial_number,
                    'basic_number' => $basic_number,
                    'rack_type' => $rack_type[$key_code],
                    'description' => 'Description for '. $serial_number,
                    'created_at' => $timestamps,
                    'updated_at' => $timestamps,
                ];
                $racks_data [] = $rack;
            }
        }

        Rack::insert($racks_data);
    }
}
