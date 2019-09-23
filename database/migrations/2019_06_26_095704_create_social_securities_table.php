<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialSecuritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_securities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('min',6,2)->nullable()->default(0.00);
            $table->decimal('max',6,2)->nullable()->default(0.00);
            $table->decimal('salary',6,2)->nullable()->default(0.00);
            $table->decimal('sss_er',6,2)->nullable()->default(0.00);
            $table->decimal('sss_ee',6,2)->nullable()->default(0.00);
            $table->decimal('sss_total',6,2)->nullable()->default(0.00);
            $table->decimal('sss_ec_er',6,2)->nullable()->default(0.00);
            $table->decimal('total_contribution_er',6,2)->nullable()->default(0.00);
            $table->decimal('total_contribution_ee',6,2)->nullable()->default(0.00);
            $table->decimal('total_contribution_total',6,2)->nullable()->default(0.00);
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
        Schema::dropIfExists('social_securities');
    }
}
