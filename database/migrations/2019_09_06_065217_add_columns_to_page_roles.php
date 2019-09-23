<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToPageRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('page_roles', function (Blueprint $table) {
             $table->boolean('full')->default(0)->nullable();
             $table->boolean('view')->default(0)->nullable();
             $table->boolean('add')->default(0)->nullable();
             $table->boolean('edit')->default(0)->nullable();
             $table->boolean('delete')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('page_roles', function (Blueprint $table) {
            //
        });
    }
}
