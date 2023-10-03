<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCoalitionToAlliancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alliances', function (Blueprint $table) {
            $table->bigInteger('alliance_coalition');
 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alliances', function (Blueprint $table) {
            $table->bigInteger('alliance_coalition');
        });
    }
}
