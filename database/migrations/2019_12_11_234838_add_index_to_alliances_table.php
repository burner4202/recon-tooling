<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToAlliancesTable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alliances', function(Blueprint $table)
        {
            $table->index('alliance_alliance_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alliances', function (Blueprint $table)
        {
            $table->dropIndex(['alliance_alliance_id']);
        });
    }
}
