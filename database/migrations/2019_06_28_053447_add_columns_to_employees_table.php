<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
			$table->string('employee_name')->nullable();
			$table->date('hiredate')->nullable();
			$table->string('job_title', 255)->nullable();
			$table->decimal('annual_salary',10,2)->nullable()->default(0.00);
			$table->decimal('regular_hourly_rate',10,2)->nullable()->default(0.00);
			$table->decimal('overtime_hourly_rate',10,2)->nullable()->default(0.00);
			$table->integer('pay_frequency')->nullable()->default(0); // 0 - Monthly, 1 - Biweekly
			$table->decimal('yearly_holidays',10,2)->nullable()->default(0.00);
			$table->decimal('yearly_vacations',10,2)->nullable()->default(0.00);
			$table->decimal('yearly_sick_days',10,2)->nullable()->default(0.00);
			$table->boolean('overtime_exemption')->default(0); // 0 - No, 1 - Yes
			$table->string('federal_filing_status', 80)->nullable();
			$table->integer('tax_allowance')->nullable()->default(0); // 0 - No, 1 - Yes
			$table->decimal('401k_contribution',10,2)->nullable()->default(0.00);
			$table->decimal('pretax_withholdings_other',10,2)->nullable()->default(0.00);
			$table->decimal('state_tax',10,2)->nullable()->default(0.00);
			$table->decimal('local_tax',10,2)->nullable()->default(0.00);
			$table->decimal('social_security',10,2)->nullable()->default(0.00);
			$table->decimal('medicare',10,2)->nullable()->default(0.00);
			$table->decimal('posttax_deductin_insurance',10,2)->nullable()->default(0.00);
			$table->decimal('posttax_deductin_other',10,2)->nullable()->default(0.00);
			$table->text('address')->after('posttax_deductin_other')->change();
			$table->string('city', 255)->nullable();
			$table->string('state', 255)->nullable();
			$table->string('zipcode', 255)->nullable();
			$table->string('sss_last_4', 4)->nullable();
			
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('employees');
    }
}
