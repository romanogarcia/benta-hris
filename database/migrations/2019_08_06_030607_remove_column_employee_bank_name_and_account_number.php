<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveColumnEmployeeBankNameAndAccountNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
            Schema::table("employees", function ($table) {
                if(Schema::hasColumn('employees', 'bank_name')){
                    $table->dropColumn('bank_name'); // delete column
                }
                if(Schema::hasColumn('employees', 'bank_account_number')){
                    $table->dropColumn('bank_account_number'); // delete column
                }
            });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
