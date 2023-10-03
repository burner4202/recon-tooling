<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToCorporationsTable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('corporation', function(Blueprint $table)
        {
            $table->index('corporation_corporation_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('corporation', function (Blueprint $table)
        {
            $table->dropIndex(['corporation_corporation_id']);
        });
    }
}
