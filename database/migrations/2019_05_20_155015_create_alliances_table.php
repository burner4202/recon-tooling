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

class CreateAlliancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('alliances', function (Blueprint $table) {

    		$table->increments('id');
    		$table->bigInteger('alliance_alliance_id');
    		$table->bigInteger('alliance_creator_corporation_id');
    		$table->bigInteger('alliance_creator_id');
    		$table->string('alliance_date_founded');
    		$table->bigInteger('alliance_executor_corporation_id');
    		$table->string('alliance_name');
    		$table->string('alliance_ticker');
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
    	Schema::dropIfExists('alliances');
    }
}
