<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PermissionCategory;

class PermissionCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id' => 1,
                'name' => 'Permissions by Role',
                'description' => 'Permission for base on Group / Role.',
            ],
            [
                'id' => 2,
                'name' => 'Master Data Menu',
                'description' => 'Permission Category for Master Data Menu.',
            ],
            [
                'id' => 3,
                'name' => 'Master Data Manage',
                'description' => 'Permission Category for Manage Master Data.',
            ],
            [
                'id' => 4,
                'name' => 'General Menu',
                'description' => 'Permission Category for Master Data Menu.',
            ],
            [
                'id' => 5,
                'name' => 'Menu Print',
                'description' => 'Permission Category for Print.',
            ],
        ];

        foreach ($data as $key => $permissionCategory) {
            PermissionCategory::create($permissionCategory);
        }
    }
}
