<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAllianceToCharacterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::table('characters', function (Blueprint $table) {
    		$table->string('character_corporation_name');
    		$table->bigInteger('character_alliance_id');
    		$table->string('character_alliance_name');

    	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	Schema::table('characters', function (Blueprint $table) {
    		$table->string('character_corporation_name');
    		$table->bigInteger('character_alliance_id');
    		$table->string('character_alliance_name');
    	});
    }
}
