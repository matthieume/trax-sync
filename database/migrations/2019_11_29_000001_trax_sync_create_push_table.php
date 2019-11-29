<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TraxSyncCreatePushTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trax_sync_push', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('statement_id');
            $table->tinyInteger('connector')->unsigned();
            $table->smallInteger('error')->unsigned();
            $table->smallInteger('attempts')->unsigned();

            // Indexes.
            $table->index('connector');
            $table->index('error');
            $table->index('attempts');

            // Foreign keys.
            $table->foreign('statement_id')->references('id')->on('trax_xapiserver_statements')
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
        Schema::table('trax_sync_push', function (Blueprint $table) {
            $table->dropForeign('trax_sync_push_statement_id_foreign');
        });
        Schema::dropIfExists('trax_sync_push');
    }
}
