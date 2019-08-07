<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePivotRolePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pivot_role_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('rbac_role_id')->comment('关联角色');
            $table->integer('rbac_permission_id')->comment('关联权限');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pivot_role_permissions');
    }
}
