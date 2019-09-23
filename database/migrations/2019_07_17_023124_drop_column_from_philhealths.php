<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnFromPhilhealths extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('philhealths', function (Blueprint $table) {
            $table->string('salary_bracket')->change();
            $table->dropColumn('salary_rate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('philhealths', function (Blueprint $table) {
            //
        });
    }
}
