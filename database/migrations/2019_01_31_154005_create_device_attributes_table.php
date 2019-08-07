<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('attribute_key_id')->comment('属性键编号');
            $table->integer('attribute_value_id')->comment('属性值编号');
            $table->integer('spu_id')->comment('SPU编号');
            $table->integer('sku_id')->comment('SKU编号');
            $table->integer('button_image_id')->comment('按钮图片编号')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_attributes');
    }
}
