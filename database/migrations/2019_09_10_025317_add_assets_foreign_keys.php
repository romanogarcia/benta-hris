<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAssetsForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assets', function(Blueprint $table) {
            $table->foreign('added_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('location_id')->references('id')->on('asset_locations')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('category_id')->references('id')->on('asset_categories')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('supplier_id')->references('id')->on('asset_suppliers')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::table('assets_recent_employees', function(Blueprint $table) {
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade')->onUpdate('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("assets", function ($table) {
            $table->dropForeign(['added_by']); // fk first
            $table->dropForeign(['employee_id']); // fk first
            $table->dropForeign(['location_id']); // fk first$table->dropForeign(['user_id']); // fk first
            $table->dropForeign(['category_id']); // fk first
            $table->dropForeign(['supplier_id']); // fk first

            $table->dropColumn('added_by'); // then column
            $table->dropColumn('employee_id'); // then column
            $table->dropColumn('location_id'); // then column
            $table->dropColumn('category_id'); // then column
            $table->dropColumn('supplier_id'); // then column
        });

        Schema::table('assets_recent_employees', function(Blueprint $table) {
            $table->dropForeign(['asset_id']); // fk first
            $table->dropForeign(['employee_id']); // fk first

            $table->dropColumn('asset_id'); // then column
            $table->dropColumn('employee_id'); // then column
        });
    }
}
