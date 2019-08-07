<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('name',191)->comment('产品名称');
            $table->string('open_code',191)->comment('唯一设备编号')->unqiue();
            $table->string('longitude',50)->comment('纬度')->nullable();
            $table->string('latitude',50)->comment('精度')->nullable();
            $table->tinyInteger('level')->comment('等级')->default(0);
            $table->integer('sort')->comment('排序依据')->default(0);
            $table->integer('organization_id')->comment('机构编号');
            $table->integer('spu_id')->comment('SPU编号');
            $table->integer('sku_id')->comment('SKU编号');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
}
