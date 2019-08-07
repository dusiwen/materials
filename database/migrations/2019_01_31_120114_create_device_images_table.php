<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_images', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('name',50)->comment('名称')->nullable();
            $table->string('description',191)->comment('描述')->nullable();
            $table->integer('spu_id')->comment('SPU编号');
            $table->string('src',191)->comment('图片地址');
            $table->integer('sort')->comment('排序依据')->nullable()->default(0);
            $table->enum('position',['GROUP','DESCRIPTION','SKU_BUTTON'])->comment('位置');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_images');
    }
}
