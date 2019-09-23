<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexAndForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendances', function(Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->index()->change();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('leave_requests', function(Blueprint $table) {
            $table->unsignedBigInteger('user_id')->index()->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('payrolls', function(Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->index()->change();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade')->onUpdate('cascade');
        });

        // Schema::table('users', function(Blueprint $table) {
        //     $table->unsignedBigInteger('role')->index()->nullable()->change();
        //     $table->foreign('role')->references('id')->on('roles')->onDelete('set null')->onUpdate('cascade');
        // });

        Schema::table('employees', function(Blueprint $table) {
            
            $table->unsignedBigInteger('department_id')->index()->nullable()->change();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null')->onUpdate('cascade');

            $table->unsignedBigInteger('employment_status_id')->index()->nullable()->change();
            $table->foreign('employment_status_id')->references('id')->on('employment_statuses')->onDelete('set null')->onUpdate('cascade');
            
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
            $table->dropForeign(['user_id']); // fk first
            $table->dropForeign(['department_id']); // fk first
            $table->dropForeign(['employment_status_id']); // fk first
            $table->dropColumn('user_id'); // then column
            $table->dropColumn('department_id'); // then column
            $table->dropColumn('employment_status_id'); // then column
        });

        Schema::table("users", function ($table) {
            // $table->dropForeign(['role']); // fk first
            $table->dropColumn('role'); // then column
        });
        
    }
}
