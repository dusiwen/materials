<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMaintainsAddColumnProvinceCityTownStreeNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('maintains', function (Blueprint $table) {
            $table->string('province')->comment('省')->nullable();
            $table->string('city')->comment('市')->nullable();
            $table->string('town')->comment('区县')->nullable();
            $table->string('street')->comment('街道')->nullable();
            $table->string('number')->comment('门牌号')->nullable();
            $table->string('extra_detail')->comment('额外详情')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('maintains', function (Blueprint $table) {
            //
        });
    }
}
