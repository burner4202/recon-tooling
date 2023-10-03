<?php

/*
 * Goonswarm Federation Recon Tools
 *
 * Developed by scopehone <scopeh@gmail.com>
 * In conjuction with Natalya Spaghet & Mindstar Technology 
 *
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityTrackerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('activity_tracker', function (Blueprint $table) {

    		$table->increments('at_id');
    		$table->bigInteger('at_user_id');
    		$table->bigInteger('at_structure_id');
    		$table->text('at_structure_hash');
    		$table->text('at_structure_name');
    		$table->bigInteger('at_system_id');
    		$table->text('at_system_name');
    		$table->bigInteger('at_corporation_id');
    		$table->text('at_corporation_name');
    		$table->text('at_username');
    		$table->text('at_action');
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
    	Schema::dropIfExists('activity_tracker');
    }
}
