<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModulesTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules_tables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('module_name');
            $table->text('module_link')->nullable();
            $table->unsignedInteger('parent')->default(0);
            $table->unsignedInteger('priority');
            $table->string('menu_icon')->nullable();
            $table->unsignedInteger('status')->default(1);
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
        Schema::dropIfExists('modules_tables');
    }
}
