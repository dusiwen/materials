<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseReportProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_report_products', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('draw_person_name', 11)->comment('领取人姓名')->nullable();
            $table->string('draw_person_phone', 11)->comment('领取人手机号')->nullable();
            $table->integer('out_person_id')->comment('出库员工');
            $table->dateTime('outed_at')->comment('出库时间')->nullable();
            $table->dateTime('installed_at')->comment('安装时间')->nullable();
            $table->integer('maintain_id')->comment('台账地址')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_report_products');
    }
}
