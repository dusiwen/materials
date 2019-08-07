<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRbacMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rbac_menus', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('title',50)->comment('菜单名称')->unique();
            $table->integer('parent_id')->comment('父级菜单编号')->nullable();
            $table->integer('sort')->comment('排序')->default(0);
            $table->string('icon',50)->comment('图表名称')->nullable();
            $table->string('uri',191)->comment('统一资源标识')->nullable();
            $table->string('permission_id')->comment('关联权限')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rbac_menus');
    }
}
