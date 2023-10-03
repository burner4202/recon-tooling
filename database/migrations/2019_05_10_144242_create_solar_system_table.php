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

class CreateSolarSystemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

    	Schema::create('solar_system', function (Blueprint $table) {

    		$table->increments('id');
    		$table->bigInteger('ss_system_id');
    		$table->string('ss_system_name');
    		$table->string('ss_security_class');
    		$table->decimal('ss_security_status', 8,2);
    		$table->integer('ss_constellation_id');
    		$table->string('ss_constellation_name');
    		$table->integer('ss_region_id');
    		$table->string('ss_region_name');
    		$table->json('ss_position');
    		$table->json('ss_stargates');
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
    	Schema::dropIfExists('solar_system');
    }
}

