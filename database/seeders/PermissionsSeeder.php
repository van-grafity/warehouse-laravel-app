<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission_categories = [
            'permission_by_role' => 1,
            'admin_menu' => 2,
            'general_menu' => 3,
            'master_data_menu' => 4,
            'manage_fabric_menu' => 5,
            'manage_rack_menu' => 6,
        ];

        $permission_list = [
            ['name' => 'developer.access', 'description' => 'Access all permissions', 'permission_category_id' => $permission_categories['permission_by_role']],
            ['name' => 'admin.access', 'description' => 'Can access admin permissions.', 'permission_category_id' => $permission_categories['permission_by_role']],
            ['name' => 'user.access', 'description' => 'Can access default permissions.', 'permission_category_id' => $permission_categories['permission_by_role']],
            ['name' => 'guest.access', 'description' => 'Can access guest permissions.', 'permission_category_id' => $permission_categories['permission_by_role']],


            ['name' => 'admin-menu.access', 'description' => 'Can access Admin Menu.', 'permission_category_id' => $permission_categories['admin_menu']],
            ['name' => 'department.access', 'description' => 'Can access department features.', 'permission_category_id' => $permission_categories['admin_menu']],
            ['name' => 'department.manage', 'description' => 'Can manage department features.', 'permission_category_id' => $permission_categories['admin_menu']],
            
            
            ['name' => 'master-data.access', 'description' => 'Can access Master Data Menu', 'permission_category_id' => $permission_categories['master_data_menu']],
            ['name' => 'color.access', 'description' => 'Can access color features.', 'permission_category_id' => $permission_categories['master_data_menu']],
            ['name' => 'color.manage', 'description' => 'Can manage color features.', 'permission_category_id' => $permission_categories['master_data_menu']],
            ['name' => 'supplier.access', 'description' => 'Can access supplier features.', 'permission_category_id' => $permission_categories['master_data_menu']],
            ['name' => 'supplier.manage', 'description' => 'Can manage supplier features.', 'permission_category_id' => $permission_categories['master_data_menu']],
            ['name' => 'location-row.access', 'description' => 'Can access location row features.', 'permission_category_id' => $permission_categories['master_data_menu']],
            ['name' => 'location-row.manage', 'description' => 'Can manage location row features.', 'permission_category_id' => $permission_categories['master_data_menu']],
            ['name' => 'location.access', 'description' => 'Can access location features.', 'permission_category_id' => $permission_categories['master_data_menu']],
            ['name' => 'location.manage', 'description' => 'Can manage location features.', 'permission_category_id' => $permission_categories['master_data_menu']],
            ['name' => 'rack.access', 'description' => 'Can access rack features.', 'permission_category_id' => $permission_categories['master_data_menu']],
            ['name' => 'rack.manage', 'description' => 'Can manage rack features.', 'permission_category_id' => $permission_categories['master_data_menu']],
            ['name' => 'rack.print-barcode', 'description' => 'Can manage print barcode.', 'permission_category_id' => $permission_categories['master_data_menu']],
            
            
            ['name' => 'invoice.access', 'description' => 'Can access invoice features.', 'permission_category_id' => $permission_categories['general_menu']],
            ['name' => 'invoice.manage', 'description' => 'Can manage invoice features.', 'permission_category_id' => $permission_categories['general_menu']],
            ['name' => 'packinglist.access', 'description' => 'Can access packinglist features.', 'permission_category_id' => $permission_categories['general_menu']],
            ['name' => 'packinglist.manage', 'description' => 'Can manage packinglist features.', 'permission_category_id' => $permission_categories['general_menu']],
            ['name' => 'packinglist.print-qrcode', 'description' => 'Can manage print qrcode.', 'permission_category_id' => $permission_categories['general_menu']],

            
            ['name' => 'manage-fabric.access', 'description' => 'Can access Manage Fabric Menu.', 'permission_category_id' => $permission_categories['manage_fabric_menu']],
            ['name' => 'fabric-stock-in.access', 'description' => 'Can access stock in features.', 'permission_category_id' => $permission_categories['manage_fabric_menu']],
            ['name' => 'fabric-stock-in.manage', 'description' => 'Can manage stock in features.', 'permission_category_id' => $permission_categories['manage_fabric_menu']],
            ['name' => 'fabric-request.access', 'description' => 'Can access fabric request features.', 'permission_category_id' => $permission_categories['manage_fabric_menu']],
            ['name' => 'fabric-request.manage', 'description' => 'Can manage fabric request features.', 'permission_category_id' => $permission_categories['manage_fabric_menu']],
            ['name' => 'fabric-request.print', 'description' => 'Can manage print fabric request.', 'permission_category_id' => $permission_categories['manage_fabric_menu']],
            ['name' => 'fabric-request.issuance-note', 'description' => 'Can print issuance note.', 'permission_category_id' => $permission_categories['manage_fabric_menu']],
            ['name' => 'fabric-request.issuance-note-full', 'description' => 'Can print issuance note full.', 'permission_category_id' => $permission_categories['manage_fabric_menu']],
            ['name' => 'fabric-request.sync', 'description' => 'Can sync fabric request.', 'permission_category_id' => $permission_categories['manage_fabric_menu']],
            ['name' => 'fabric-status.access', 'description' => 'Can access fabric status features.', 'permission_category_id' => $permission_categories['manage_fabric_menu']],
            ['name' => 'fabric-status.manage', 'description' => 'Can manage fabric status features.', 'permission_category_id' => $permission_categories['manage_fabric_menu']],
            ['name' => 'fabric-status.change', 'description' => 'Can change rack in fabric status features.', 'permission_category_id' => $permission_categories['manage_fabric_menu']],
            ['name' => 'fabric-status.remove', 'description' => 'Can remove fabric roll in fabric status features.', 'permission_category_id' => $permission_categories['manage_fabric_menu']],
            
            
            ['name' => 'manage-rack.access', 'description' => 'Can access Manage Fabric Menu.', 'permission_category_id' => $permission_categories['manage_rack_menu']],
            ['name' => 'rack-location.access', 'description' => 'Can access rack location features.', 'permission_category_id' => $permission_categories['manage_rack_menu']],
            ['name' => 'rack-location.manage', 'description' => 'Can manage rack location features.', 'permission_category_id' => $permission_categories['manage_rack_menu']],

            
            ['name' => 'instore-report.print', 'description' => 'Can manage instore report.', 'permission_category_id' => $permission_categories['general_menu']],
        ];

        foreach ($permission_list as $permission) {
            Permission::create($permission);
        }
    }
}
