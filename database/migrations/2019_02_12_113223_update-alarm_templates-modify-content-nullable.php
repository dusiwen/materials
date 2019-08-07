<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAlarmTemplatesModifyContentNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alarm_templates', function (Blueprint $table) {
            $table->text('content')->comment('提示内容')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void`1
     */
    public function down()
    {
        Schema::table('alarm_templates', function (Blueprint $table) {
            //
        });
    }
}
