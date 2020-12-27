<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLogIdToOnDutyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('on_duty', function (Blueprint $table) {
            $table->unsignedBigInteger('log_id')->after('user_id')->nullable();
            $table->foreign('log_id')
                ->references('id')
                ->on('po_logs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('on_duty', function (Blueprint $table) {
            $table->dropForeign('on_duty_log_id_foreign');
            $table->dropColumn('log_id');
        });
        Schema::enableForeignKeyConstraints();
    }
}
