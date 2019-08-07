<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePivotFromWarehouseProductToWarehouseProductPartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pivot_from_warehouse_product_to_warehouse_product_parts', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('warehouse_product_id')->comment('成品编号');
            $table->integer('warehouse_product_part_id')->comment('零件编号');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pivot_from_warehouse_product_to_warehouse_product_parts');
    }
}
