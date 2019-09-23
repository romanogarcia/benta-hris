<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnCompanyDateFormatAndCountryId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function(Blueprint $table) {
            $table->dropColumn('country');
            $table->unsignedBigInteger('country_id')->index()->nullable()->after('city')->default('177'); //default 177 country Philippines
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null')->onUpdate('cascade');
            
            $table->enum('date_format', ['m-d-Y', 'Y-m-d', 'd-m-Y'])->default('m-d-Y')->after('company_logo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("companies", function ($table) {
            $table->dropForeign(['country_id']); // fk first
            $table->dropColumn('country_id'); // then column
        });
    }
}
