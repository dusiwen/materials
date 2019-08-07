<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateWarehouseProductInstancesAddColumnFactoryIdFactoryDeviceCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_product_instances', function (Blueprint $table) {
            $table->integer('factory_id')->comment('工厂编号')->nullable();
            $table->string('factory_device_code')->comment('厂家编码')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouse_product_instances', function (Blueprint $table) {
            //
        });
    }
}
