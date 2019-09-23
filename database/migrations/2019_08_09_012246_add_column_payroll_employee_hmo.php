<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPayrollEmployeeHmo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->decimal('hmo',8,2)->default('0.00')->after('pagibig');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("payrolls", function ($table) {
            if(Schema::hasColumn('payrolls', 'hmo')){
                $table->dropColumn('hmo'); // delete column
            }
        });
    }
}
