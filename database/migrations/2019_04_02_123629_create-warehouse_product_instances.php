<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseProductInstances extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_product_instances', function (Blueprint $table) {
            $table->integer('warehouse_product_id')->comment('整件编号');
            $table->enum('status',['BUY_IN','INSTALLED','FIX_BY_SEND','FIX_AT_TIME','FIX_TO_OUT','SCRAP'])->comment('状态')->default('BUY_IN');
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
