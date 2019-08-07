<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePivotRoleAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pivot_role_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('rbac_role_id')->comment('绑定角色');
            $table->integer('account_id')->comment('绑定用户');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pivot_role_accounts');
    }
}
