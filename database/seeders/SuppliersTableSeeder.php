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
        $max_data = 1;
        
        for ($i=0; $i < $max_data; $i++) { 
            $supplier = [
                'supplier' => 'Maxim',
                'description' => 'Official Supplier Ghimli Indonesia',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            Supplier::create($supplier);
        }
    }
}
