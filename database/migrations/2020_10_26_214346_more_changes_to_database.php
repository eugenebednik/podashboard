<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MoreChangesToDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_server_id_foreign');
            $table->dropColumn('server_id');
            $table->dropColumn('is_admin');
            $table->dropColumn('active');
        });
        Schema::enableForeignKeyConstraints();

        Schema::create('server_user', function (Blueprint $table) {
            $table->unsignedBigInteger('server_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('server_id')
                ->references('id')
                ->on('servers')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        Schema::create('allowed_roles', function (Blueprint $table) {
             $table->id();
             $table->string('role_id');
             $table->string('role_name');
             $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
