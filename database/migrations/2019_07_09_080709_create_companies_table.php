<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('company_name');
            $table->string('email');
            $table->string('address', 255);
            $table->string('extra_address', 255)->nullable();
            $table->string('zip_code', 20)->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('website')->nullable();
            $table->string('business_number')->nullable();
            $table->string('tax_number')->nullable();
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
        Schema::dropIfExists('companies');
    }
}
