<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Database\Seeders\PermissionCategoriesTableSeeder;
use Database\Seeders\RolePermissionsSeeder;
use Database\Seeders\DepartmentsTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\ColorsTableSeeder;
use Database\Seeders\SuppliersTableSeeder;
use Database\Seeders\InvoicesTableSeeder;
use Database\Seeders\LocationRowsTableSeeder;
use Database\Seeders\LocationsTableSeeder;
use Database\Seeders\RacksTableSeeder;



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(PermissionCategoriesTableSeeder::class);
        $this->call(RolePermissionsSeeder::class);
        $this->call(DepartmentsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ColorsTableSeeder::class);
        // $this->call(SuppliersTableSeeder::class);
        $this->call(InvoicesTableSeeder::class);
        $this->call(LocationRowsTableSeeder::class);
        $this->call(LocationsTableSeeder::class);
        $this->call(RacksTableSeeder::class);
    }
}
