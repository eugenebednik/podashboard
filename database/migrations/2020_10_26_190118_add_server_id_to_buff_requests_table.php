<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddServerIdToBuffRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buff_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('server_id')->after('id');
            $table->foreign('server_id')
                ->references('id')
                ->on('servers')
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
        Schema::disableForeignKeyConstraints();
        Schema::table('buff_requests', function (Blueprint $table) {
            $table->dropForeign('buff_requests_server_id_foreign');
            $table->dropColumn('server_id');
        });
        Schema::enableForeignKeyConstraints();
    }
}
