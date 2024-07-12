<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     *
     * @return void
     */
    public function run()
    {
        // create default user
        $user = \App\Models\User::factory()->create([
            'name' => 'Developer',
            'email' => 'developer@example.com',
            'password' => Hash::make('123456789'),
            'department_id' => '1',
        ]);
        $user->syncRoles('developer');
        $user->syncPermissions('user.access');
        
        
        $user = \App\Models\User::factory()->create([
            'name' => 'Admin App',
            'email' => 'admin@example.com',
            'password' => Hash::make('123456789'),
            'department_id' => '3',
        ]);
        $user->syncRoles('admin');
        
        $user = \App\Models\User::factory()->create([
            'name' => 'User Default',
            'email' => 'user@example.com',
            'password' => Hash::make('123456789'),
            'department_id' => '8',
        ]);
        $user->syncRoles('user');

        $user = \App\Models\User::factory()->create([
            'name' => 'Warehouse Supervisor',
            'email' => 'warehouse_supervisor@example.com',
            'password' => Hash::make('123456789'),
            'department_id' => '3',
        ]);
        $user->syncRoles('warehouse-supervisor');
        
        $user = \App\Models\User::factory()->create([
            'name' => 'Warehouse Clerk',
            'email' => 'warehouse_clerk@example.com',
            'password' => Hash::make('123456789'),
            'department_id' => '3',
        ]);
        $user->syncRoles('warehouse-clerk');

        $user = \App\Models\User::factory()->create([
            'name' => 'FG Warehouse',
            'email' => 'fg_warehouse@ghimli.com',
            'password' => Hash::make('123456789'),
            'department_id' => '3',
        ]);
        $user->syncRoles('fg-warehouse');

        $user = \App\Models\User::factory()->create([
            'name' => 'Admin Ghimli',
            'email' => 'admin@ghimli.com',
            'password' => Hash::make('ghimli@2024'),
            'department_id' => '1',
        ]);
        $user->syncRoles('developer');

    }
}
