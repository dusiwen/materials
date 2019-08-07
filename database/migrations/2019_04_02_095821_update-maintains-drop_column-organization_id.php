<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMaintainsDropColumnOrganizationId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('maintains', function (Blueprint $table) {
            $table->dropColumn('organization_id');
            $table->string('organization_code',50)->comment('机构代码')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('maintains', function (Blueprint $table) {
            //
        });
    }
}
