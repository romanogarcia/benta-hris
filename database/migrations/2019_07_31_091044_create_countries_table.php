<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('country_code')->nullable();
            $table->string('country_name')->nullable();
            $table->string('currency_code')->nullable();
            $table->string('fips_code')->nullable();
            $table->string('iso_numeric')->nullable();
            $table->string('north')->nullable();
            $table->string('south')->nullable();
            $table->string('east')->nullable();
            $table->string('west')->nullable();
            $table->string('capital')->nullable();
            $table->string('continent_name')->nullable();
            $table->string('continent')->nullable();
            $table->string('languages')->nullable();
            $table->string('iso_alpha_3')->nullable();
            $table->string('geo_name_id')->nullable();
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
        Schema::dropIfExists('countries');
    }
}
