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

class CreateTaskManagerTable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
     	Schema::create('task_manager', function (Blueprint $table) {
     		$table->increments('id');
     		$table->integer('tm_created_by_user_id');
     		$table->text('tm_created_by_user_username');
     		$table->integer('tm_solar_system_id');
     		$table->text('tm_solar_system_name');
     		$table->integer('tm_constellation_id');
     		$table->text('tm_constellation_name');
     		$table->integer('tm_region_id');
     		$table->text('tm_region_name');
     		$table->text('tm_task')->nullable();
     		$table->text('tm_prority');
     		$table->text('tm_notes');
     		$table->datetime('tm_created_datetime_at');
     		/*
     		 * 0 = Pending
     		 * 1 = Created, Awaiting Dispatch
     		 * 2 = Dispatched push to Outstanding
     		 * 3 = Claimed, Not Completed
     		 * 4 = Completed
     		*/
     		$table->text('tm_state');
     		$table->integer('tm_accepted_by_user_id');
     		$table->text('tm_accepted_by_user_username');
     		$table->datetime('tm_accepted_datetime_at');
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
    	Schema::dropIfExists('task_manager');
    }
}
