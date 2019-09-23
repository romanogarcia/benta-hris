<?php

use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->insert([
            [
                'company_name'      => 'Bentacos Information Technology Services',
                'email'             => 'business@bentacos.com',
                'address'           => 'Unit 2708 Tycoon Center Pearl Drive, Ortigas Center Pasig City 1605 Philippines',
            ],
        ]);
    }
}
