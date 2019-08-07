<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_videos', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('name', 191)->comment('产品名称');
            $table->string('open_code', 191)->comment('唯一设备编号')->unqiue();
            $table->string('longitude', 50)->comment('纬度')->nullable();
            $table->string('latitude', 50)->comment('精度')->nullable();
            $table->tinyInteger('level')->comment('等级')->default(0);
            $table->integer('sort')->comment('排序依据')->nullable()->default(0);
            $table->integer('organization_id')->comment('机构编号');
            $table->integer('spu_id')->comment('SPU编号');
            $table->integer('sku_id')->comment('SKU编号');
            $table->integer('device_group_id')->comment('设备分组编号');
            $table->boolean('is_group')->comment('是否是集成设备')->nullable()->default(true);
            $table->integer('device_category_id')->comment('设备类目编号')->nullable();
            $table->integer('device_status_id')->comment('设备状态')->nullable()->default(1);
            $table->float('battery_voltage')->comment('电池电压')->nullable()->default(0);
            $table->float('electric_quantity')->comment('电池电量')->nullable()->default(0);
            $table->float('temperature')->comment('设备温度')->nullable()->default(0);
            $table->boolean('is_need_photograph_time')->comment('是否需要更新拍照时间')->nullable()->default(false);
            $table->float('total_working_time')->comment('总运行时间')->nullable()->default(0);
            $table->float('working_time')->comment('单次运行时间')->default(0);
            $table->float('signal_4g')->comment('4G信号')->nullable()->default(0);
            $table->float('signal_2g')->comment('2G信号')->nullable()->default(0);
            $table->float('free_ram')->comment('RAM余量')->nullable()->default(0);
            $table->float('free_rom')->comment('ROM余量')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_videos');
    }
}
