<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlarmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alarms', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('title',191)->comment('报警标题');
            $table->text('description')->comment('报警描述');
            $table->integer('template_id')->comment('模板编号');
            $table->integer('spu_id')->comment('spu编号');
            $table->integer('sku_id')->comment('sku编号');
            $table->integer('organization_id')->comment('机构编号');
            $table->integer('line_id')->comment('线路编号');
            $table->integer('device_group_id')->comment('设备分组编号');
            $table->integer('device_open_id')->comment('设备开放标识');
            $table->integer('report_id')->comment('记录数据编号');
            $table->string('report_table_name',191)->comment('记录数据表名称');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alarms');
    }
}
