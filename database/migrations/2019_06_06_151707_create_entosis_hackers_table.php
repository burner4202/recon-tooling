<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntosisHackersTable extends Migration
{
	/**
     * Run the migrations.
     *
     * @return void
     */
	public function up()
	{
		Schema::create('entosis_hackers', function (Blueprint $table) {
			$table->increments('id');
			$table->string('eh_campaign_id');
			$table->string('eh_target_system');
			$table->bigInteger('eh_user_id');
			$table->string('eh_username');
			$table->bigInteger('eh_character_id');
			$table->string('eh_character_name');
			$table->bigInteger('eh_character_alliance_id');
			$table->string('eh_character_alliance_name');
			$table->bigInteger('eh_location_system_id');
			$table->string('eh_location_system_name');
			$table->bigInteger('eh_ship_type_id');
			$table->string('eh_ship_type_name');
			$table->datetime('eh_registered_at');
			$table->bigInteger('eh_status');

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
    	Schema::dropIfExists('entosis_hackers');
    }
}
