<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionAssigner extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles_permissions = [
            'developer' => [
                'developer.access',
                'admin.access',
                'user.access',
                'warehouse-supervisor.access',
                'fg-warehouse.access',
            ],
            'admin' => [
                'admin.access',
                'user.access',
                'master-data.access',
                'color.access',
                'supplier.access',
                'department.access',
                'location-row.access',
                'location.access',
                'rack.access',
                'invoice.access',
                'packinglist.access',
                'manage-fabric.access',
                'stock-in.access',
                'fabric-request.access',
            ],
            'user' => [
                'user.access',
            ],
            'warehouse-supervisor' => [
                'user.access',
                'warehouse-supervisor.access',
            ],
            'fg-warehouse' => [
                'user.access',
                'fg-warehouse.access',
                'master-data.access',
                'rack.access',
                'rack.print-barcode',
            ],
        ];

        foreach ($roles_permissions as $role_name => $permissions) {
            $role = Role::findByName($role_name);
            $role->syncPermissions($permissions);
        }
    }
}
