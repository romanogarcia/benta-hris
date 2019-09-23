<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnsFromUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('users', 'phone'))
        {
            Schema::table('users', function (Blueprint $table)
            {
                $table->dropColumn('phone');

            });
        }
		if (Schema::hasColumn('users', 'user_image'))
        {
            Schema::table('users', function (Blueprint $table)
            {
                $table->dropColumn('user_image');

            });
        }
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
