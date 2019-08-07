<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportSensorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_sensors', function (Blueprint $table) {
//            $table->increments('id');
            $table->string('id');
            $table->primary('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('device_open_code',191)->comment('设备唯一标识');
            $table->string('report_no',191)->comment('上报流水号');
            $table->integer('template_id')->comment('模板编号');
            $table->string('source_data',191)->comment('原始数据');
            $table->float('value')->comment('解析后数据');
            $table->string('unit',191)->comment('解析后单位');
            $table->integer('level')->comment('报警等级')->default(0);
            $table->datetime('client_datetime')->comment('设备时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_sensors');
    }
}
