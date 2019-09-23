<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagibigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagibigs', function (Blueprint $table) {
            $table->bigIncrements('id');
             $table->decimal('monthly_compensation',7,2)->nullable()->default(0.00);
              $table->decimal('employee_share',7,2)->nullable()->default(0.00);
              $table->decimal('employer_share',7,2)->nullable()->default(0.00);
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
        Schema::dropIfExists('pagibigs');
    }
}
