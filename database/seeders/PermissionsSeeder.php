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
            'master_data_menu' => 2,
            'master_data_manage' => 3,
            'general_menu' => 4,
            'menu_print' => 5,
        ];

        $permission_list = [
            ['name' => 'developer.access', 'description' => 'Access all permissions', 'permission_category_id' => $permission_categories['permission_by_role']],
            ['name' => 'admin.access', 'description' => 'Can access admin permissions.', 'permission_category_id' => $permission_categories['permission_by_role']],
            ['name' => 'user.access', 'description' => 'Can access default permissions.', 'permission_category_id' => $permission_categories['permission_by_role']],
            ['name' => 'guest.access', 'description' => 'Can access guest permissions.', 'permission_category_id' => $permission_categories['permission_by_role']],
            ['name' => 'warehouse-supervisor.access', 'description' => 'Can access warehouse supervisor permissions.', 'permission_category_id' => $permission_categories['permission_by_role']],
            ['name' => 'fg-warehouse.access', 'description' => 'Can access fg warehouse permissions.', 'permission_category_id' => $permission_categories['permission_by_role']],
            ['name' => 'master-data.access', 'description' => 'Can access Master Data Menu', 'permission_category_id' => $permission_categories['master_data_menu']],
            ['name' => 'color.access', 'description' => 'Can access color features.', 'permission_category_id' => $permission_categories['master_data_menu']],
            ['name' => 'color.manage', 'description' => 'Can manage color features.', 'permission_category_id' => $permission_categories['master_data_manage']],
            ['name' => 'supplier.access', 'description' => 'Can access supplier features.', 'permission_category_id' => $permission_categories['master_data_menu']],
            ['name' => 'supplier.manage', 'description' => 'Can manage supplier features.', 'permission_category_id' => $permission_categories['master_data_manage']],
            ['name' => 'department.access', 'description' => 'Can access department features.', 'permission_category_id' => $permission_categories['master_data_menu']],
            ['name' => 'department.manage', 'description' => 'Can manage department features.', 'permission_category_id' => $permission_categories['master_data_manage']],
            ['name' => 'location-row.access', 'description' => 'Can access location row features.', 'permission_category_id' => $permission_categories['master_data_menu']],
            ['name' => 'location-row.manage', 'description' => 'Can manage location row features.', 'permission_category_id' => $permission_categories['master_data_manage']],
            ['name' => 'location.access', 'description' => 'Can access location features.', 'permission_category_id' => $permission_categories['master_data_menu']],
            ['name' => 'location.manage', 'description' => 'Can manage location features.', 'permission_category_id' => $permission_categories['master_data_manage']],
            ['name' => 'rack.access', 'description' => 'Can access rack features.', 'permission_category_id' => $permission_categories['master_data_menu']],
            ['name' => 'rack.manage', 'description' => 'Can manage rack features.', 'permission_category_id' => $permission_categories['master_data_manage']],
            ['name' => 'rack.print-barcode', 'description' => 'Can manage print barcode.', 'permission_category_id' => $permission_categories['menu_print']],
            ['name' => 'invoice.access', 'description' => 'Can access invoice features.', 'permission_category_id' => $permission_categories['master_data_menu']],
            ['name' => 'manage-fabric.access', 'description' => 'Can access Manage Fabric Menu.', 'permission_category_id' => $permission_categories['master_data_menu']],
            ['name' => 'invoice.manage', 'description' => 'Can manage invoice features.', 'permission_category_id' => $permission_categories['master_data_manage']],
            ['name' => 'packinglist.access', 'description' => 'Can access packinglist features.', 'permission_category_id' => $permission_categories['master_data_menu']],
            ['name' => 'packinglist.manage', 'description' => 'Can manage packinglist features.', 'permission_category_id' => $permission_categories['master_data_manage']],
            ['name' => 'stock-in.access', 'description' => 'Can access stock in features.', 'permission_category_id' => $permission_categories['master_data_menu']],
            ['name' => 'stock-in.manage', 'description' => 'Can manage stock in features.', 'permission_category_id' => $permission_categories['master_data_manage']],
            ['name' => 'fabric-request.access', 'description' => 'Can access fabric request features.', 'permission_category_id' => $permission_categories['master_data_menu']],
            ['name' => 'fabric-request.manage', 'description' => 'Can manage fabric request features.', 'permission_category_id' => $permission_categories['master_data_manage']],
            ['name' => 'fabric-status.access', 'description' => 'Can access fabric status features.', 'permission_category_id' => $permission_categories['master_data_menu']],
            ['name' => 'fabric-status.manage', 'description' => 'Can manage fabric status features.', 'permission_category_id' => $permission_categories['master_data_manage']],
            ['name' => 'instore-report.print', 'description' => 'Can manage instore report.', 'permission_category_id' => $permission_categories['menu_print']],
            ['name' => 'packinglist.print-qrcode', 'description' => 'Can manage print barcode.', 'permission_category_id' => $permission_categories['menu_print']],
        ];

        foreach ($permission_list as $permission) {
            Permission::create($permission);
        }
    }
}
