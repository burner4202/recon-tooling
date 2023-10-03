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

class CreateConstellationsTable extends Migration
{
/**
     * Run the migrations.
     *
     * @return void
     */
public function up()
{

	Schema::create('constellations', function (Blueprint $table) {

		$table->increments('id');
		$table->bigInteger('con_constellation_id');
		$table->string('con_constellation_name');
		$table->bigInteger('con_region_id');
		$table->string('con_region_name');
		$table->json('con_systems');
		$table->json('con_position');
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
    	Schema::dropIfExists('constellations');
    }
}
