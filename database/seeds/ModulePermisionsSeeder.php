<?php

use Illuminate\Database\Seeder;

class ModulePermisionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // \App\module_permission::truncate();
            $to_import = public_path('sql/module_permissions.sql');
            DB::unprepared(file_get_contents($to_import));
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
