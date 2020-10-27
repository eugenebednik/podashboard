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

        Schema::create('server_admins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('server_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
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
