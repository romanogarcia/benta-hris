<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnEmployeeBankId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function(Blueprint $table) {
            $table->unsignedBigInteger('employee_bank_id')->index()->after('extra_address')->nullable();
            $table->foreign('employee_bank_id')->references('id')->on('employee_banks')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("employees", function ($table) {
            $table->dropForeign(['employee_bank_id']); // fk first
            $table->dropColumn('employee_bank_id'); // then column
        });
    }
}
