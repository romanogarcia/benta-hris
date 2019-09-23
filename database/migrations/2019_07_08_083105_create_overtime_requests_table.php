<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOvertimeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overtime_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index();
            $table->date('date_start'); 
            $table->date('date_end'); 
            $table->time('time_start'); 
            $table->time('time_end');
            $table->longText('reason');
            $table->string('type');
            $table->string('approved_by')->nullable();
            $table->date('approved_date')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Declined', 'Rescheduled', 'Cancel']);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('overtime_requests');
    }
}
