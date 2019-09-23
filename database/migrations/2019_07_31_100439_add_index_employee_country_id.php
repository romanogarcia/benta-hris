<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexEmployeeCountryId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function(Blueprint $table) {
            $table->unsignedBigInteger('country_id')->index()->after('zipcode')->nullable();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null')->onUpdate('cascade');
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
            $table->dropForeign(['country_id']); // fk first
            $table->dropColumn('country_id'); // then column
        });
    }
}
