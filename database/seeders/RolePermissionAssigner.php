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
                'fabric-stock-in.access',
                'fabric-request.access',
            ],
            'user' => [
                'user.access',
            ],
            'warehouse-supervisor' => [
                'invoice.access',
                'invoice.manage',
                'packinglist.access',
                'packinglist.manage',
                'packinglist.print-qrcode',
                
                'master-data.access',
                'color.access',
                'color.manage',
                'supplier.access',
                'supplier.manage',
                'location-row.access',
                'location-row.manage',
                'location.access',
                'location.manage',
                'rack.access',
                'rack.manage',
                'rack.print-barcode',
                
                
                'manage-fabric.access',
                'fabric-stock-in.access',
                'fabric-stock-in.manage',
                'fabric-request.access',
                'fabric-request.manage',
                'fabric-status.access',
                'fabric-status.manage',
                'fabric-request.print',
                'fabric-request.issuance-note',


                'manage-rack.access',
                'rack-location.access',
                'rack-location.manage',
                

                'instore-report.print',

            ],
            'warehouse-clerk' => [
                'invoice.access',
                'packinglist.access',
                'packinglist.print-qrcode',
                
                'master-data.access',
                'color.access',
                'supplier.access',
                'location-row.access',
                'location.access',
                'rack.access',
                'rack.print-barcode',

                'manage-fabric.access',
                'fabric-stock-in.access',
                'fabric-request.access',
                'fabric-status.access',


                'manage-rack.access',
                'rack-location.access',
            ],
            'fg-warehouse' => [
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
