<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetScannedBarcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_scanned_barcodes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('barcode');
            $table->unsignedBigInteger('uploaded_by')->index()->nullable(); // uploaded or scanned by the user_id
            $table->unsignedBigInteger('location_id')->index()->nullable();  //Connected on asset_locations
            $table->unsignedBigInteger('scanned_barcode_upload_id')->index()->nullable(); //connected on upload_scanned_barcode  table
            $table->timestamps();

            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('location_id')->references('id')->on('asset_locations')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('scanned_barcode_upload_id')->references('id')->on('asset_scanned_barcode_uploads')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset_scanned_barcodes');
    }
}
