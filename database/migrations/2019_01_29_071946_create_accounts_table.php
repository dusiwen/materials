<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('account', 191)->comment('登录用账号')->unique();
            $table->string('password', 191)->comment('登录用密码');
            $table->string('nickname', 191)->comment('昵称')->nullable(true)->unique();
            $table->string('email', 191)->comment('邮箱')->nullable(true)->unique();
            $table->string('phone', 11)->comment('手机号')->nullable(true)->unique();
            $table->integer('status_id')->comment('状态')->default(1);
            $table->string('open_id', 32)->comment('开放编号')->unique(true);
            $table->integer('organization_id')->comment('机构编号')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
