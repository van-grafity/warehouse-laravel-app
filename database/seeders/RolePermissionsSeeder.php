<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\Hash;

class RolePermissionsSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     *
     * @return void
     */
    public function run()
    {
        // ## Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ## create roles
        $role_list = [
            [
                'name' => 'developer',
                'title' => 'Developer',
                'description' => 'IT Programmer',
            ],
            [
                'name' => 'admin',
                'title' => 'Admin',
                'description' => 'Day to day administrators of the site.',
            ],
            [
                'name' => 'user',
                'title' => 'User',
                'description' => 'General users of the site.',
            ],
            [
                'name' => 'guest',
                'title' => 'Guest',
                'description' => 'Guest User.',
            ],
        ];
        foreach ($role_list as $key => $role) {
            Role::create($role);
        }

        // ## create permissions
        $permission_list = [
            [
                'name' => 'developer.access',
                'description' => 'Access all permissions',
                'permission_category_id' => '1',
            ],
            [
                'name' => 'admin.access',
                'description' => 'Can access admin permissions.',
                'permission_category_id' => '1',
            ],
            [
                'name' => 'user.access',
                'description' => 'Can access default permissions.',
                'permission_category_id' => '1',
            ],
            [
                'name' => 'guest.access',
                'description' => 'Can access guest permissions.',
                'permission_category_id' => '1',
            ],
            [
                'name' => 'master-data.access',
                'description' => 'Can access Master Data Menu',
                'permission_category_id' => '2',
            ],
            [
                'name' => 'color.access',
                'description' => 'Can access color features.',
                'permission_category_id' => '2',
            ],
            [
                'name' => 'color.manage',
                'description' => 'Can manage color features.',
                'permission_category_id' => '3',
            ],
            [
                'name' => 'supplier.access',
                'description' => 'Can access supplier features.',
                'permission_category_id' => '2',
            ],
            [
                'name' => 'supplier.manage',
                'description' => 'Can manage supplier features.',
                'permission_category_id' => '3',
            ],
            [
                'name' => 'department.access',
                'description' => 'Can access department features.',
                'permission_category_id' => '2',
            ],
            [
                'name' => 'department.manage',
                'description' => 'Can manage department features.',
                'permission_category_id' => '3',
            ],
                        [
                'name' => 'location.access',
                'description' => 'Can access location features.',
                'permission_category_id' => '2',
            ],
            [
                'name' => 'location.manage',
                'description' => 'Can manage location features.',
                'permission_category_id' => '3',
            ],
            [
                'name' => 'rack.access',
                'description' => 'Can access rack features.',
                'permission_category_id' => '2',
            ],
            [
                'name' => 'rack.manage',
                'description' => 'Can manage rack features.',
                'permission_category_id' => '3',
            ],
            [
                'name' => 'invoice.access',
                'description' => 'Can access invoice features.',
                'permission_category_id' => '2',
            ],
            [
                'name' => 'invoice.manage',
                'description' => 'Can manage invoice features.',
                'permission_category_id' => '3',
            ],
            [
                'name' => 'packinglist.access',
                'description' => 'Can access packinglist features.',
                'permission_category_id' => '2',
            ],
            [
                'name' => 'packinglist.manage',
                'description' => 'Can manage packinglist features.',
                'permission_category_id' => '3',
            ],
            [
                'name' => 'fabric-offloading.access',
                'description' => 'Can access fabric offloading features.',
                'permission_category_id' => '2',
            ],
            [
                'name' => 'fabric-offloading.manage',
                'description' => 'Can manage fabric offloading features.',
                'permission_category_id' => '3',
            ],
            [
                'name' => 'stock-in.access',
                'description' => 'Can access stock in features.',
                'permission_category_id' => '2',
            ],
            [
                'name' => 'stock-in.manage',
                'description' => 'Can manage stock in features.',
                'permission_category_id' => '3',
            ],
            [
                'name' => 'fabric-request.access',
                'description' => 'Can access fabric request features.',
                'permission_category_id' => '2',
            ],
            [
                'name' => 'fabric-request.manage',
                'description' => 'Can manage fabric request features.',
                'permission_category_id' => '3',
            ],
        ];
        foreach ($permission_list as $key => $permission) {
            Permission::create($permission);
        }

        // ## Give Permission to Role
        $developer_role = Role::findByName('developer');
        $developer_role->syncPermissions([
            'developer.access',
            'admin.access',
            'user.access',
            'master-data.access',
            'invoice.access',
            'packinglist.access',
        ]);

        $admin_role = Role::findByName('admin');
        $admin_role->syncPermissions([
            'admin.access',
            'user.access',
            'master-data.access',
            'color.access',
            'supplier.access',
            'department.access',
            'rack.access',
            'location.access',
        ]);

        $user_role = Role::findByName('user');
        $user_role->syncPermissions([
            'user.access',
        ]);
    }
}