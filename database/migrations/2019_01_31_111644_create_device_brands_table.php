<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_brands', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('title',191)->comment('名称')->unique();
            $table->string('description',191)->comment('描述')->nullable();
            $table->string('logo',191)->comment('logo')->nullable();
            $table->integer('status_id')->comment('状态')->default(1);
            $table->string('official_home_link',191)->comment('官网地址')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_brands');
    }
}
