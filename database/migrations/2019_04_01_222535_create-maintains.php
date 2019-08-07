<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaintains extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('maintains', function (Blueprint $table) {
            $table->softDeletes();
            $table->string('address_nickname', 50)->comment('地址别名')->nullable()->unique();
            $table->string('longitude')->comment('经度')->nullable();
            $table->string('latitude')->comment('维度')->nullable();
            $table->integer('organization_id')->comment('所属机构')->nullable();
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
