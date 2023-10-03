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

class CreateKnownStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	Schema::create('known_structures', function (Blueprint $table) {

		$table->increments('id');
		$table->text('str_structure_id_md5');
		$table->bigInteger('str_system_id');
		$table->bigInteger('str_type_id');
		$table->text('str_name');
		$table->string('str_type');
		$table->string('str_distance');
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
    	Schema::dropIfExists('known_structures');
    }
}
