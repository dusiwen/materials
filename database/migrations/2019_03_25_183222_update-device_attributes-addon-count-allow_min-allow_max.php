<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDeviceAttributesAddonCountAllowMinAllowMax extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_attributes', function (Blueprint $table) {
            $table->integer('count')->comment('所需数量')->nullable()->default(1);
            $table->double('allow_min', 18, 6)->comment('正常值范围起点')->nullable()->default(0);
            $table->double('allow_max', 18, 6)->comment('正常值范围重点')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('device_attributes', function (Blueprint $table) {
            //
        });
    }
}
