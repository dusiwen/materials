<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehouseProductPartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_product_parts', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('name', 50)->comment('名称');
            $table->string('subscript', 50)->comment('下标')->nullable();
            $table->string('prefix_name', 50)->comment('前缀名')->nullable();
            $table->string('prefix_subscript', 50)->comment('前缀名下标')->nullable();
            $table->string('inventory', 50)->comment('库存')->nullable()->default();
            $table->string('character', 50)->comment('测试特性')->nullable()->default();
            $table->date('storage_at', 50)->comment('存储时间')->nullable();
            $table->string('allow_min', 50)->comment('最小值')->nullable()->default(0);
            $table->string('allow_max', 50)->comment('最大值')->nullable();
            $table->string('unit', 50)->comment('单位')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_product_parts');
    }
}
