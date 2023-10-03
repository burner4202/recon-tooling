<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntosisNodeTable extends Migration
{
	/**
     * Run the migrations.
     *
     * @return void
     */
	public function up()
	{
		Schema::create('entosis_nodes', function (Blueprint $table) {
			$table->increments('id');
			$table->string('en_campaign_id');
			$table->string('en_target_system');
			$table->string('en_node_id');
			$table->bigInteger('en_added_by_user_id');
			$table->string('en_added_by_username');
			$table->bigInteger('en_allocated_character_id');
			$table->string('en_allocated_character_name');
			$table->bigInteger('en_node_system_id');
			$table->string('en_node_system_name');
			$table->datetime('en_registered_at');
			$table->datetime('en_est_completed');
			$table->integer('en_node_status');
			$table->datetime('en_completed_at');

    		/*
    		 * 0 = Unclaimed
    		 * 1 = Claimed, On My Way
    		 * 2 = Ready to go
    		 * 3 = Warm up Cycle
    		 * 4 = Pause
    		 * 5 = Completed
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
    	Schema::dropIfExists('entosis_nodes');
    }
}
