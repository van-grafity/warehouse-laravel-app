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
    }
}
