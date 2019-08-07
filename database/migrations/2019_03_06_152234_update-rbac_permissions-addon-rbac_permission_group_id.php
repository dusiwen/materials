<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRbacPermissionsAddonRbacPermissionGroupId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rbac_permissions', function (Blueprint $table) {
            $table->integer('rbac_permission_group_id')->comment('权限分组编号')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rbac_permissions', function (Blueprint $table) {
            //
        });
    }
}
