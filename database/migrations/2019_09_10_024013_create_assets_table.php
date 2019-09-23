<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('property_number')->nullable(); //barcode number
            $table->text('item_description')->nullable();
            $table->string('asset_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('mr_number')->nullable();
            $table->string('purchase_order_number')->nullable();
            $table->decimal('acquisition_cost', 10, 2)->nullable()->default('0');
            $table->date('date_acquired')->nullable();
            $table->string('condition')->nullable();
            $table->string('warranty')->nullable();
            $table->string('slug_token')->unique();

            $table->unsignedBigInteger('added_by')->index();  //Who add the asset
            $table->unsignedBigInteger('employee_id')->index(); //accountable employee
            $table->unsignedBigInteger('location_id')->index()->nullable(); //Location of asset
            $table->unsignedBigInteger('supplier_id')->index()->nullable(); //Supplier of asset
            $table->unsignedBigInteger('category_id')->index()->nullable(); //Category of asset
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
        Schema::dropIfExists('assets');
    }
}
