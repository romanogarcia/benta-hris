<?php

use Illuminate\Database\Seeder;

class EmploymentStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('employment_statuses')->insert([
           [
               'name' => 'Casual',
               'created_at' => now()
           ],
           [
               'name' => 'Seasonal',
               'created_at' => now()
           ],
            [
                'name' => 'Project',
                'created_at' => now()
            ],
            [
                'name' => 'Term',
                'created_at' => now()
            ]
        ]);
    }
}
