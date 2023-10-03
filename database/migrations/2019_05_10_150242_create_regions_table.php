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

class CreateRegionsTable extends Migration
{
 /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

    	Schema::create('regions', function (Blueprint $table) {

    		$table->increments('id');
    		$table->bigInteger('reg_region_id');
    		$table->string('reg_region_name');
    		$table->text('reg_description');
    		$table->json('reg_constellations');
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
    	Schema::dropIfExists('regions');
    }
}
