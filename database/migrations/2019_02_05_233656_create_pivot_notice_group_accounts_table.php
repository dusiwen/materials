<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePivotNoticeGroupAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pivot_notice_group_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('notice_group_id')->comment('通知组编号');
            $table->integer('account_id')->comment('用户编号');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pivot_notice_group_accounts');
    }
}
