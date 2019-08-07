<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('name',191)->comment('机构名称')->unique();
            $table->integer('parent_id')->comment('父级编号')->nullable();
            $table->integer('level')->comment('等级')->default(0);
            $table->string('db_conn_str',191)->comment('数据库链接');
            $table->boolean('is_main')->comment('是否是主体机构')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organizations');
    }
}
