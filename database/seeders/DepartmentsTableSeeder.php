<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'department' => 'IT Programmer',
                'description' => 'Department of IT Programmer',
            ],
            [
                'department' => 'IT Officer',
                'description' => 'Department of IT Officer',
            ],
            [
                'department' => 'Warehouse',
                'description' => 'Department of Warehouse',
            ],
            [
                'department' => 'Cutting',
                'description' => 'Department of Cutting',
            ],
            [
                'department' => 'Sewing',
                'description' => 'Department of Sewing',
            ],
            [
                'department' => 'PPC',
                'description' => 'Department of PPC',
            ],
            [
                'department' => 'PMR',
                'description' => 'Department of PMR',
            ],
            [
                'department' => 'General User',
                'description' => 'General User',
            ],
            [
                'department' => 'Guest',
                'description' => 'Guest User',
            ],
        ];

        foreach ($data as $key => $department) {
            Department::create($department);
        }
    }
}
