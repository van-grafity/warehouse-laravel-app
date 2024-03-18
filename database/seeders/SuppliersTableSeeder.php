<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SuppliersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $max_data = 100;
        
        for ($i=0; $i < $max_data; $i++) { 
            $supplier = [
                'supplier' => 'Maxim '. $i + 1,
                'description' => 'Official Supplier Ghimli Indonesia Number '.$i + 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            Supplier::create($supplier);
        }
    }
}
