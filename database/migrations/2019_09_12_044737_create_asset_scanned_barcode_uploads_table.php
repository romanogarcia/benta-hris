<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetScannedBarcodeUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_scanned_barcode_uploads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('file');
            $table->string('file_name')->nullable();
            $table->string('file_extension')->nullable();
            $table->text('description')->nullable();
            $table->string('slug_token')->unique();
            $table->unsignedBigInteger('uploaded_by')->index()->nullable(); //user_id
            $table->timestamps();

            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset_scanned_barcode_uploads');
    }
}
