<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntosisScoutsTable extends Migration
{

	/**
     * Run the migrations.
     *
     * @return void
     */
	public function up()
	{
		Schema::create('entosis_scouts', function (Blueprint $table) {
			$table->increments('id');
			$table->string('es_campaign_id');
			$table->string('es_target_system');
			$table->bigInteger('es_user_id');
			$table->string('es_username');
			$table->bigInteger('es_character_id');
			$table->string('es_character_name');
			$table->bigInteger('es_character_alliance_id');
			$table->string('es_character_alliance_name');
			$table->bigInteger('es_location_system_id');
			$table->string('es_location_system_name');
			$table->bigInteger('es_ship_type_id');
			$table->string('es_ship_type_name');
			$table->datetime('es_registered_at');
			$table->bigInteger('es_status');

    		/* 
    		 * 0 = Unregistered
    		 * 1 = Registered
    		 */

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
    	Schema::dropIfExists('entosis_scouts');
    }
}

