<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateWarehouseReportProductsAddColumen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_report_products', function (Blueprint $table) {
            $table->integer('in_person_id')->comment('仓库入库人编号')->nullable();
            $table->string('send_person_name',50)->comment('送至仓库人姓名')->nullable();
            $table->string('send_person_phone',11)->comment('送至仓库人电话')->nullable();
            $table->dropColumn('installed_at');
            $table->integer('out_person_id')->comment('仓库出库人编号')->nullable()->change();
            $table->integer('in_at')->comment('入库时间')->nullable();
            $table->string('in_reason')->comment('入库原因')->nullable();
            $table->string('out_reason')->comment('出库原因')->nullable();
            $table->text('description')->comment('备注描述')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouse_report_products', function (Blueprint $table) {
            //
        });
    }
}
