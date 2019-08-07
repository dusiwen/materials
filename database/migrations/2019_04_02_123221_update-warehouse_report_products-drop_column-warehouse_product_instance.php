<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateWarehouseReportProductsDropColumnWarehouseProductInstance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_report_products', function (Blueprint $table) {
            $table->dropColumn('warehouse_product_instance');
            $table->integer('warehouse_product_instance_id')->comment('整件实例编号');
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
