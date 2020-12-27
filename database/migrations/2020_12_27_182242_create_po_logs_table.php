<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePOLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('server_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('logged_in_at')->nullable();
            $table->timestamp('logged_out_at')->nullable();

            $table->foreign('server_id')->references('id')->on('servers');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('po_logs');
    }
}
