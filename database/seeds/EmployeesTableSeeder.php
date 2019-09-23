<?php

use Illuminate\Database\Seeder;

class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('employees')->insert([
            'user_id'              => 2,
            'employee_number'      => '001906281',
            'first_name'           => 'Rey Jhon',
            'last_name'            => 'Baquirin',
            'middle_name'          => 'ABARRACOSO',
            'gender'               => 'M',
            'birthdate'            => '1996-02-08',
            'civil_status'         => 'Single',
            'address'              => 'Phase 6 Camarin Caloocan City',
            'email'                => 'reyjhonbaquirin@yahoo.com',
            'home_phone'           => '09193317525',
            'position'             => 'Developer',
            'employment_status_id' => 0,
            'is_active'            => 0,
            'basic_salary'         => '15000.00',
            'tax_status'           => 'SME1'
        ]);
    }
}
