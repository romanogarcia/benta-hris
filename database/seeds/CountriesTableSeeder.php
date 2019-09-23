<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		\App\Country::truncate();
        // Import the countries data
        $to_import = public_path('sql/countries.sql');
        DB::unprepared(file_get_contents($to_import));
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
