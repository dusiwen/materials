<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePivotRoleMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pivot_role_menus', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('rbac_role_id')->comment('角色编号');
            $table->integer('rbac_menu_id')->comment('菜单编号');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pivot_role_menus');
    }
}
