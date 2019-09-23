<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_banks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('address')->nullable();
            $table->string('extra_address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zipcode')->nullable();
            
            $table->unsignedBigInteger('country_id')->index()->nullable();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null')->onUpdate('cascade');

            $table->string('iban')->nullable();
            $table->string('bic')->nullable();
            $table->string('member_no')->nullable();
            $table->string('clearing_no')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("employee_banks", function ($table) {
            $table->dropForeign(['country_id']); // fk first
            $table->dropColumn('country_id'); // then column
        });
        Schema::dropIfExists('employee_banks');

    }
}
