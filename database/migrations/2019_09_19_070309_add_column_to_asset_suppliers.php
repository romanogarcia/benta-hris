<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToAssetSuppliers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset_suppliers', function (Blueprint $table) {
            $table->enum('type', ['Company','Person'])->after('id');
            $table->string('supplier')->nullable()->change()->after('type');
            $table->string('first_name')->nullable()->after('supplier');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('zip_code')->nullable()->after('last_name');
            $table->string('address')->nullable()->after('zip_code')->change();
            $table->string('city')->nullable()->after('address');
            $table->string('phone')->nullable()->after('city');
            $table->string('mobile')->nullable()->after('phone');
            $table->string('email')->nullable()->after('mobile');
            $table->unsignedBigInteger('country_id')->index()->nullable()->after('email');
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
        Schema::table('asset_suppliers', function (Blueprint $table) {
            //
        });
    }
}
