<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnLocationIdToAssetScannedBarcodeUploads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset_scanned_barcode_uploads', function (Blueprint $table) {
            $table->unsignedBigInteger('location_id')->index()->nullable()->after('uploaded_by');
            $table->foreign('location_id')->references('id')->on('asset_locations')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asset_scanned_barcode_uploads', function (Blueprint $table) {
            if(Schema::hasColumn('assets', 'location_id')){
                $table->dropForeign(['location_id']); // fk first
                $table->dropColumn('location_id'); // then column
            }
        });
    }
}
