<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeLenghtValueOnSocialSecuritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('social_securities', function(Blueprint $table) {
            $table->decimal('min',8,2)->nullable()->default(0.00)->change();
            $table->decimal('max',8,2)->nullable()->default(0.00)->change();
            $table->decimal('salary',8,2)->nullable()->default(0.00)->change();
            $table->decimal('sss_er',8,2)->nullable()->default(0.00)->change();
            $table->decimal('sss_ee',8,2)->nullable()->default(0.00)->change();
            $table->decimal('sss_total',8,2)->nullable()->default(0.00)->change();
            $table->decimal('sss_ec_er',8,2)->nullable()->default(0.00)->change();
            $table->decimal('total_contribution_er',8,2)->nullable()->default(0.00)->change();
            $table->decimal('total_contribution_ee',8,2)->nullable()->default(0.00)->change();
            $table->decimal('total_contribution_total',8,2)->nullable()->default(0.00)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
