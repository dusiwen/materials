<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateWarehouseReportProductsAddColumnInReason extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_report_products', function (Blueprint $table) {
            $table->enum('in_reason', ['BUY_IN', 'FIX_BY_SEND', 'FIX_AT_TIME', 'FIX_TO_OUT_FINISH'])->comment('入库原因')->default('BUY_IN');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouse_report_products', function (Blueprint $table) {
            //
        });
    }
}
