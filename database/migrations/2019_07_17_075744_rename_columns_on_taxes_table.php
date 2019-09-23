<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameColumnsOnTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('taxes', function (Blueprint $table) {
        $table->renameColumn('over','compensation_level');
        $table->renameColumn('but_not_over','over');
        $table->renameColumn('rate','tax');
        $table->decimal('rate',7,2)->change();
        $table->decimal('percentage',7,2)->after('rate');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
