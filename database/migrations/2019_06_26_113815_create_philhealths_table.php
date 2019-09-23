<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhilhealthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('philhealths', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('salary_bracket');
            $table->decimal('salary_min',7,2)->nullable()->default(0.00);
            $table->decimal('salary_max',7,2)->nullable()->default(0.00);
            $table->decimal('salary_rate',7,2)->nullable()->default(0.00);
            $table->decimal('total_monthly_premium',7,2)->nullable()->default(0.00);
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
        Schema::dropIfExists('philhealths');
    }
}
