<?php

use Illuminate\Database\Seeder;

class LeaveRequestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('leave_requests')->insert([
            [
                'user_id'           => '1',
                'state_status'      => 'Approved',
                'date_filed'        => '2019-07-11',
                'date_start'        => '2019-07-12',
                'date_end'          => '2019-07-13',
                'type'              => 'VACATION LEAVE',
                'reason'            => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley ',
            ],
            [
                'user_id'           => '1',
                'state_status'      => 'Approved',
                'date_filed'        => '2019-07-11',
                'date_start'        => '2019-07-16',
                'date_end'          => '2019-07-17',
                'type'              => 'VACATION LEAVE',
                'reason'            => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley ',
            ],

        ]);
    }
}
