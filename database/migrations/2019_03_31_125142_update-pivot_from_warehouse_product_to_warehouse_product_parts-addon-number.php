<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePivotFromWarehouseProductToWarehouseProductPartsAddonNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pivot_from_warehouse_product_to_warehouse_product_parts', function (Blueprint $table) {
            $table->tinyInteger('number')->comment('成品所需数量')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pivot_from_warehouse_product_to_warehouse_product_parts', function (Blueprint $table) {
            //
        });
    }
}
