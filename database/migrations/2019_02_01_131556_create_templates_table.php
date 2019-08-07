<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('name',191)->comment('名称');
            $table->integer('position')->comment('位置')->default(0);
            $table->string('style',191)->comment('报警样式')->nullable();
            $table->integer('level')->comment('报警等级')->default(0);
            $table->enum('condition_type',['<','<=','=','>=','>'])->comment('判断类型');
            $table->float('condition_value')->comment('阀值');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('templates');
    }
}
