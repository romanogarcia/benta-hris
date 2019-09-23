<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*================== DEPARTMENTS INITIAL DATA ==================*/
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		\App\Department::truncate();
        DB::table('departments')->insert([
            ['name' => 'Admin',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            ['name' => 'Human Resources',
            'created_at' => now(),
            'updated_at' => now(),
            ],
            ['name' => 'IT Department',
            'created_at' => now(),
            'updated_at' => now(),
            ]
        ]);
        
        /*================== EMPLOYMENT STATUS INITIAL DATA ==================*/
		\App\EmploymentStatus::truncate();
        DB::table('employment_statuses')->insert([
            [
                'name'          => 'Regular',
                'created_at'    => now(),
            ],
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
        
        /*================== LEAVE TYPES INITIAL DATA ==================*/
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		\App\Leave::truncate();
        DB::table('leaves')->insert([
            [
                'name' => 'Vacation Leave',
                'created_at' => now()
            ],
            [
                'name' => 'Sick Leave',
                'created_at' => now()
            ],
            [
                'name' => 'Service Incentive Leave',
                'created_at' => now()
            ],
            [
                'name' => 'Maternity Leave',
                'created_at' => now()
            ],
            [
                'name' => 'Paternity Leave',
                'created_at' => now()
            ],
            [
                'name' => 'Parental Leave',
                'created_at' => now()
            ],
            [
                'name' => 'Rehabilitation Leave',
                'created_at' => now()
            ],
            [
                'name' => 'Study Leave',
                'created_at' => now()
            ]
        ]);
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        /*================== COMPANY INITIAL DATA ==================*/ 
		\App\Companies::truncate();
        DB::table('companies')->insert([
            [
                'company_name'      => 'Bentacos Information Technology Services',
                'email'             => 'business@bentacos.com',
                'address'           => 'Unit 2708 Tycoon Center Pearl Drive, Ortigas Center Pasig City 1605 Philippines',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
        ]);
        
        /*================== ROUTES CONTROLLER INITIAL DATA ==================*/ 
        $roles = getRouteNames();
        for($i=1;$i<=3;$i++){
            foreach($roles as $key => $role){
                DB::table('roles')->insert([
                    'department_id'     => $i,
                    'role'              => 'full',
                    'page'              => $key,
                    'is_active'         => '1',
                    'created_at'        => now(),
                    'updated_at'        => now()
                ]);
            }
        }
        
        /*================== EMPLOYEE and USER INITIAL DATA ==================*/ 
		// \App\Employee::truncate();
		// \App\User::truncate();
        DB::table('users')->insert([
            'employee_id'       => 1,
            'name'              => 'Admin',
            'username'          => 'admin',
            'email'             => 'test@test.com',
            'role'              => '1',
            'password'          => Hash::make('Switzerland2019!'),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);
        
        DB::table('employees')->insert([
            'user_id'           => 1,
            'employee_number'   => 1,
            'last_name'         => 'Admin',
            'first_name'        => 'Bentacos',
            'email'             => 'test@test.com',
            'department_id'     => 1,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);
        
        /*================== ATTENDANCE INITIAL DATA ==================*/ 
        \App\Attendance::truncate();
        $limit = 30;
        $to_insert = '';
        for($x=1; $x <= $limit; $x++){
            if($x < 10){
                $x = '0'.$x;
            }
            DB::table('attendances')->insert([
                [
                    'at_date'       => '2019-07-'.$x,
                    'employee_id'   => '1',
                    'name'          => 'Admin Bentacos',
                    'time_in'       => '08:00 2019-07-'.$x,
                    'time_out'      => '17:00 2019-07-'.$x,
                    'total'         => 8.0,
                    'created_at'    => now(),
                    'updated_at'    => now()
                ],
            ]);
        }

        /*================== SSS INITIAL DUMMY DATA ==================*/
		\App\SocialSecurity::truncate();
        $min = 1750;
        $max = 0;
        $salary = 1500; //2000;
        $sss_er = 120; //160;
        $sss_ee = 60; //80;
        $sss_total = 240;
        $ec_er = 10;
        $er = 170;
        $ee = 80;
        $total = 36; //35;
        for($x=0; $x <= $total; $x++) {
            if($x==0){
                $min = -500;
            } elseif ($x==1) {
                $min = 1750;
                $max = 0;
            }
            $min += 500;
            $max = $x==0?2250:($min+499.99);
            $ec_er = ($sss_er + $sss_ee) >= 1740?30:10;
            
            DB::table('social_securities')->insert([
                [
                    'min' => $min,
                    'max' => $min >= 19750 ?999999:$max,
                    'salary' => $salary += 500,
                    'sss_er' => $sss_er += 40,
                    'sss_ee' => $sss_ee += 20,
                    'sss_total' => $sss_er + $sss_ee,
                    'sss_ec_er' => $ec_er,
                    'total_contribution_er' => $sss_er + $ec_er,
                    'total_contribution_ee' => $sss_ee,
                    'total_contribution_total' =>  $sss_er + $ec_er + $sss_ee,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);
        }
    
    /*================== TAX INITIAL DATA ==================*/
	 \App\Tax::truncate();	
      DB::table('taxes')->insert([
          [
              'compensation_level'  => '0',
              'over'                => '20833',
              'tax'                 => 0,
              'percentage'          => 0,
              'created_at'          => now(),
              'updated_at'          => now()
          ],
          [
              'compensation_level'  =>'20834',
              'over'                => '33332',
              'tax'                 => 0,
              'percentage'          => 20,
              'created_at'          => now(),
              'updated_at'          => now()
          ],
          [
              'compensation_level'  =>'33333',
              'over'                => '66666',
              'tax'                 => 2500,
              'percentage'          => 25,
              'created_at'          => now(),
              'updated_at'          => now()
          ],
          [
              'compensation_level'  =>'66667',
              'over'                => '166666',
              'tax'                 => 10833.33,
              'percentage'          => 30,
              'created_at'          => now(),
              'updated_at'          => now()
          ],
          [
              'compensation_level'  =>'166667',
              'over'                => '666666',
              'tax'                 => 40833.33,
              'percentage'          => 32,
              'created_at'          => now(),
              'updated_at'          => now()
          ],
          [
              'compensation_level'  =>'666667',
              'over'                => '999999',
              'tax'                 => 200833.33,
              'percentage'          => 35,
              'created_at'          => now(),
              'updated_at'          => now()
          ]
      ]);
        
      /*================== PHILHEALTH INITIAL DATA ==================*/
		\App\Philhealth::truncate();
        DB::table('philhealths')->insert([
           [
               'salary_bracket'         => '0-10000',
               'salary_min'             => '0.00',
               'salary_max'             => '10000.00',
               'total_monthly_premium'  => '275.00',
               'employee_share'         => '137.50',
               'employer_share'         => '137.50',
               'created_at'             => now(),
               'updated_at'             => now()
           ],
            [
                'salary_bracket'        => '10000.1-40000',
                'salary_min'            => '10000.1',
                'salary_max'            => '40000.00',
                'total_monthly_premium' => '1100.00',
                'employee_share'        => '550.00',
                'employer_share'        => '550.50',
                'created_at'            => now(),
                'updated_at'            => now()
            ]
        ]);
        
        /*================== PAG-IBIG INITIAL DATA ==================*/
		\App\Pagibig::truncate();
        DB::table('pagibigs')->insert([
            [
                'monthly_compensation'  => '0-1500',
                'employee_share'        => '1',
                'employer_share'        => '2',
                'created_at'            => now(),
                'updated_at'            => now()
            ],
            [
                'monthly_compensation'  => '1501-999999',
                'employee_share'        => '2',
                'employer_share'        => '2',
                'created_at'            => now(),
                'updated_at'            => now()
            ]
        ]);
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    }
}
