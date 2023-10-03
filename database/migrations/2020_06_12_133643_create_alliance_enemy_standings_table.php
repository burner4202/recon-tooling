<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllianceEnemyStandingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('alliance_enemy_standings', function (Blueprint $table) {
    		$table->increments('id');
    		$table->bigInteger('as_contact_id')->index();
    		$table->bigInteger('as_enemy_alliance_id')->index();
    		$table->string('as_enemy_alliance_name')->index();
    		$table->string('as_contact_type');
    		$table->string('as_character_name');
    		$table->bigInteger('as_corporation_id');
    		$table->string('as_corporation_name');
    		$table->bigInteger('as_alliance_id')->index();
    		$table->string('as_alliance_name');
    		$table->decimal('as_standing', 5, 2);
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
    	Schema::dropIfExists('alliance_enemy_standings');
    }
}
