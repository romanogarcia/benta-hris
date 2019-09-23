<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CountriesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ModuleTableSeeder::class);
        $this->call(PageRoleTableSeeder::class);
        $this->call(ModulePermisionsSeeder::class);

        // $this->call(LeavesTableSeeder::class);
        // $this->call(UsersTableSeeder::class);
        // $this->call(EmployeesTableSeeder::class);
        // $this->call(AttendancesTableSeeder::class); 
        // $this->call(LeaveRequestTableSeeder::class); 
        // $this->call(CompaniesTableSeeder::class); 
    }
}
