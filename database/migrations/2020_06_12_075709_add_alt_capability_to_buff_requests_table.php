<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAltCapabilityToBuffRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buff_requests', function (Blueprint $table) {
            $table->boolean('is_alt_request')->default(false)->after('handled_by');
            $table->string('alt_name')->nullable()->default(null)->index();
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
            $table->dropColumn('alt_name');
            $table->dropColumn('is_alt_request');
        });
        Schema::enableForeignKeyConstraints();
    }
}
