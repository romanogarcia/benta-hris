<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index();
            $table->string('employee_number', 20)->nullable();
            $table->string('first_name', 200)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('middle_name', 100)->nullable();
            $table->string('gender', 10)->nullable();
            $table->date('birthdate')->nullable();
            $table->string('civil_status', 80)->nullable();
            $table->string('personal_phone', 20)->nullable();
            $table->string('home_phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('email', 500)->nullable();

            $table->unsignedBigInteger('employment_status_id')->nullable();

            $table->boolean('is_active')->default(1)->nullable(); //0=inactive, 1=active

            $table->double('basic_salary')->nullable();
            $table->double('transportation_allowance')->nullable();
            $table->double('food_allowance')->nullable();
            $table->double('personal_allowance')->nullable();

            $table->string('tax_status')->nullable(); 

            $table->string('sss_number', 50)->nullable();
            $table->string('tin_number', 50)->nullable();
            $table->string('pagibig_number', 50)->nullable();
            $table->string('philhealth_number', 50)->nullable();
            $table->string('number_of_dependents', 50)->nullable();

            $table->string('dependent1', 200)->nullable();
            $table->string('dependent2', 200)->nullable();
            $table->string('dependent3', 200)->nullable();
            $table->string('dependent4', 200)->nullable();

            $table->date('dependent1_bday')->nullable();
            $table->date('dependent2_bday')->nullable();
            $table->date('dependent3_bday')->nullable();
            $table->date('dependent4_bday')->nullable();

            $table->string('dependent1_rel', 200)->nullable();
            $table->string('dependent2_rel', 200)->nullable();
            $table->string('dependent3_rel', 200)->nullable();
            $table->string('dependent4_rel', 200)->nullable();
            
            $table->string('position', 500)->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('employee_image', 200)->nullable();

            $table->string('contact_emergency_name', 200)->nullable();
            $table->string('contact_emergency_rel', 200)->nullable();
            $table->text('contact_emergency_addr')->nullable();
            $table->string('contact_emergency_phone', 20)->nullable();

            $table->timestamps();

        //    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users'); //delete main id then reference id
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
