<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuffRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buff_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_name')->index();
            $table->string('discord_snowflake')->index();
            $table->unsignedBigInteger('request_type_id')->index();
            $table->boolean('outstanding')->default(true);
            $table->unsignedBigInteger('handled_by')->nullable()->default(null)->index();
            $table->timestamps();

            $table->foreign('handled_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('request_type_id')
                ->references('id')
                ->on('request_types')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buff_requests');
    }
}
