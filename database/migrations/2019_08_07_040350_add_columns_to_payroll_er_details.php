<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToPayrollErDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payrolls', function(Blueprint $table) {
            $table->text('er_details')->after('netpay')->nullable();
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
            if(Schema::hasColumn('payrolls', 'er_details')){
                $table->dropColumn('er_details'); // delete column
            }
        });
    }
}
