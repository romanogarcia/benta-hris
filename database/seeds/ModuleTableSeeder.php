<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ModuleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		\App\modules_table::truncate();
        // Import the countries data
        $to_import = public_path('sql/modules_tables.sql');
        DB::unprepared(file_get_contents($to_import));
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        DB::table('modules_tables')->insert([
            [
                'module_name'   => 'Asset',
                'module_link'   => null,
                'parent'        => '0',
                'priority'      => '3',
                'menu_icon'     => '<i class="mdi mdi-package-variant-closed menu-icon"></i>',
                'status'        => '1',
                'route_name'    => null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'module_name'   => 'Inventory',
                'module_link'   => '/asset/inventory',
                'parent'        => '28',
                'priority'      => '1',
                'menu_icon'     => '<i class="mdi mdi-package-variant"></i>',
                'status'        => '1',
                'route_name'    => 'route("asset_inventory.index")',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'module_name'   => 'Categories',
                'module_link'   => '/asset/category',
                'parent'        => '28',
                'priority'      => '2',
                'menu_icon'     => '<i class="mdi mdi-format-list-bulleted"></i>',
                'status'        => '1',
                'route_name'    => 'route("asset_category.index")',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'module_name'   => 'Suppliers',
                'module_link'   => '/asset/supplier',
                'parent'        => '28',
                'priority'      => '3',
                'menu_icon'     => '<i class="mdi mdi-account-switch"></i>',
                'status'        => '1',
                'route_name'    => 'route("asset_supplier.index")',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'module_name'   => 'Locations',
                'module_link'   => '/asset/location',
                'parent'        => '28',
                'priority'      => '4',
                'menu_icon'     => '<i class="mdi mdi-periscope"></i>',
                'status'        => '1',
                'route_name'    => 'route("asset_location.index")',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'module_name'   => 'Scanned Barcode',
                'module_link'   => '/asset/scanned-barcode',
                'parent'        => '28',
                'priority'      => '5',
                'menu_icon'     => '<i class="mdi mdi-barcode-scan"></i>',
                'status'        => '1',
                'route_name'    => 'route("asset_scanned_barcode.index")',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'module_name'   => 'Scanned Upload',
                'module_link'   => '/asset/scanned-barcode-upload',
                'parent'        => '28',
                'priority'      => '6',
                'menu_icon'     => '<i class="mdi mdi-barcode"></i>',
                'status'        => '1',
                'route_name'    => 'route("asset_scanned_barcode_upload.index")',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'module_name'   => 'Print Custom Barcode',
                'module_link'   => '/asset/print-custom-barcode',
                'parent'        => '28',
                'priority'      => '7',
                'menu_icon'     => '<i class="mdi mdi-cloud-print-outline"></i>',
                'status'        => '1',
                'route_name'    => 'route("asset_print_custom_barcode.index")',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}
