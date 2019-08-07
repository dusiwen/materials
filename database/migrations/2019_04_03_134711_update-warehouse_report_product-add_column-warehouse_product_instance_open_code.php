<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateWarehouseReportProductAddColumnWarehouseProductInstanceOpenCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_report_products', function (Blueprint $table) {
            $table->string('warehouse_product_instance_open_code',50)->comment('设备代码');
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
