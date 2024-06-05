<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ## Clear existing roles and permissions
        $this->clearRolesAndPermissions();

        // ## Call individual seeders
        $this->call([
            PermissionCategoriesTableSeeder::class,
            RolesSeeder::class,
            PermissionsSeeder::class,
            RolePermissionAssigner::class,
        ]);
    }

    /**
     * Clear all roles and permissions from the database.
     *
     * @return void
     */
    protected function clearRolesAndPermissions()
    {
        // ## Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ## Disable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // ## Truncate pivot tables and main tables
        \DB::table('auth_role_has_permissions')->truncate();
        \DB::table('auth_permissions')->truncate();
        \DB::table('auth_roles')->truncate();
        \DB::table('auth_permissions_categories')->truncate();
        

        // ## Enable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
