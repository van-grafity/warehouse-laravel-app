<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_list = [
            ['name' => 'developer', 'title' => 'Developer', 'description' => 'IT Programmer'],
            ['name' => 'admin', 'title' => 'Admin', 'description' => 'Day to day administrators of the site.'],
            ['name' => 'user', 'title' => 'User', 'description' => 'General users of the site.'],
            ['name' => 'guest', 'title' => 'Guest', 'description' => 'Guest User.'],
            ['name' => 'warehouse-supervisor', 'title' => 'Warehouse Supervisor', 'description' => 'Warehouse Supervisor.'],
            ['name' => 'warehouse-clerk', 'title' => 'Warehouse Clerk', 'description' => 'Warehouse Clerk.'],
            ['name' => 'fg-warehouse', 'title' => 'FG Warehouse', 'description' => 'FG Warehouse.'],
        ];

        foreach ($role_list as $role) {
            Role::create($role);
        }
    }
}