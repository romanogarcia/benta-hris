<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPayorllPaymentDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payrolls', function(Blueprint $table) {
            $table->date('payment_date')->after('is_paid')->nullable();
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
            if(Schema::hasColumn('payrolls', 'payment_date')){
                $table->dropColumn('payment_date'); // delete column
            }
        });
    }
}
