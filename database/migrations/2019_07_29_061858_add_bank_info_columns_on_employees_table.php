<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBankInfoColumnsOnEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('bank_name')->after('sss_last_4')->nullable();
            $table->string('bank_account_number')->after('bank_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            if(Schema::hasColumn('employees', 'bank_name')){
                $table->dropColumn('bank_name'); // delete column
            }
            if(Schema::hasColumn('employees', 'bank_account_number')){
                $table->dropColumn('bank_account_number'); // delete column
            }
        });
    }
}
