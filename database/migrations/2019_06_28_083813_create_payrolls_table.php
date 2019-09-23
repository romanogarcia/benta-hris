<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('billing_number');
            $table->string('employee_id');
            $table->string('name');
            $table->string('period');
            $table->double('total_hours', 8, 2);
            $table->text('overtimes');
            $table->text('allowances');
            $table->double('gross', 8, 2);
            $table->text('sss');
            $table->text('philhealth');
            $table->text('pagibig');
            $table->double('total_deduction');
            $table->double('basic_pay', 8, 2);
            $table->text('tax');
            $table->double('netpay', 8, 2);
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
        Schema::dropIfExists('payrolls');
    }
}
