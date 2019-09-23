<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToAssetLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset_locations', function (Blueprint $table) {
            $table->string('zip_code')->nullable()->after('location');
            $table->string('address')->nullable()->after('zip_code');
            $table->string('city')->nullable()->after('address');
            $table->unsignedBigInteger('country_id')->index()->nullable()->after('city');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asset_locations', function (Blueprint $table) {
            //
        });
    }
}
