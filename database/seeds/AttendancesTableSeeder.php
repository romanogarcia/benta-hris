<?php

use Illuminate\Database\Seeder;

class AttendancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $limit = 30;
        $to_insert = '';

        for($x=1; $x <= $limit; $x++){
            if($x < 10){
                $x = '0'.$x;
            }
            DB::table('attendances')->insert([
                ['at_date' => '2019-07-'.$x,
                'employee_id' => '2',
                'name' => 'Rey Jhon Baquirin',
                'time_in' => '08:00 2019-07-'.$x,
                'time_out' => '17:00 2019-07-'.$x,
                'total' => 8.0],
            ]);
        }


        
    }
}