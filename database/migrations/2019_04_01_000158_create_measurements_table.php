<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeasurementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measurements', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('warehouse_product_id')->comment('成品编号');
            $table->integer('warehouse_product_part_id')->comment('零件编号');
            $table->string('key', 50)->comment('测试项名称');
            $table->string('allow_min', 50)->comment('允许最小值')->default(0);
            $table->string('allow_max', 50)->comment('允许最大值')->default(0);
            $table->string('unit')->comment('单位')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('measurements');
    }
}
