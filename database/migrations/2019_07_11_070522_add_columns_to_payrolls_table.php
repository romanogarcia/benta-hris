<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToPayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->boolean('payroll_type')->after('netpay')->default(0);
            $table->date('payroll_date')->after('payroll_type');
            $table->text('description')->after('payroll_date')->nullable();
            $table->text('notes')->after('description')->nullable();
            $table->boolean('is_paid')->after('notes')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn('payroll_type');
            $table->dropColumn('payroll_date');
            $table->dropColumn('description');
            $table->dropColumn('notes');
            $table->dropColumn('is_paid');
        });
    }
}
