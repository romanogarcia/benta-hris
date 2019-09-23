<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class PageRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::statement('SET FOREIGN_KEY_CHECKS=0;');
      \App\Page_Role::truncate();
          $to_import = public_path('sql/page_roles.sql');
          DB::unprepared(file_get_contents($to_import));
      DB::statement('SET FOREIGN_KEY_CHECKS=1;');
      
      DB::table('page_roles')->insert([
        [
            'page_name'   => 'Asset',
            'permissions' => 'full|ADD|VIEW|EDIT|DELETE',
            'full'        => '1',
            'view'        => '0',
            'add'         => '0',
            'edit'        => '0',
            'delete'      => '0',
            'module_id'   => '28',
            'created_at'  => now(),
            'updated_at'  => now(),
        ],
        [
          'page_name'   => 'Inventory',
          'permissions' => 'full|ADD|VIEW|EDIT|DELETE',
          'full'        => '1',
          'view'        => '0',
          'add'         => '0',
          'edit'        => '0',
          'delete'      => '0',
          'module_id'   => '29',
          'created_at'  => now(),
          'updated_at'  => now(),
        ],
        [
          'page_name'   => 'Categories',
          'permissions' => 'full|ADD|VIEW|EDIT|DELETE',
          'full'        => '1',
          'view'        => '0',
          'add'         => '0',
          'edit'        => '0',
          'delete'      => '0',
          'module_id'   => '30',
          'created_at'  => now(),
          'updated_at'  => now(),
        ],
        [
          'page_name'   => 'Suppliers',
          'permissions' => 'full|ADD|VIEW|EDIT|DELETE',
          'full'        => '1',
          'view'        => '0',
          'add'         => '0',
          'edit'        => '0',
          'delete'      => '0',
          'module_id'   => '31',
          'created_at'  => now(),
          'updated_at'  => now(),
        ],
        [
          'page_name'   => 'Locations',
          'permissions' => 'full|ADD|VIEW|EDIT|DELETE',
          'full'        => '1',
          'view'        => '0',
          'add'         => '0',
          'edit'        => '0',
          'delete'      => '0',
          'module_id'   => '32',
          'created_at'  => now(),
          'updated_at'  => now(),
        ],
        [
          'page_name'   => 'Scanned Barcode',
          'permissions' => 'full|ADD|VIEW|EDIT|DELETE',
          'full'        => '1',
          'view'        => '0',
          'add'         => '0',
          'edit'        => '0',
          'delete'      => '0',
          'module_id'   => '33',
          'created_at'  => now(),
          'updated_at'  => now(),
        ],
        [
          'page_name'   => 'Scanned Upload',
          'permissions' => 'full|ADD|VIEW|EDIT|DELETE',
          'full'        => '1',
          'view'        => '0',
          'add'         => '0',
          'edit'        => '0',
          'delete'      => '0',
          'module_id'   => '34',
          'created_at'  => now(),
          'updated_at'  => now(),
        ],
        [
          'page_name'   => 'Print Custom Barcode',
          'permissions' => 'full|ADD|VIEW|EDIT|DELETE',
          'full'        => '1',
          'view'        => '0',
          'add'         => '0',
          'edit'        => '0',
          'delete'      => '0',
          'module_id'   => '35',
          'created_at'  => now(),
          'updated_at'  => now(),
        ],
    ]);

    
    
    }
}
