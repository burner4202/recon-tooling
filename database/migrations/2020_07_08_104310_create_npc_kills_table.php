<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNpcKillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('npc_kills', function (Blueprint $table) {

    		$table->increments('id');
    		$table->string('npc_kill_id')->index();
    		$table->bigInteger('solar_system_id')->index();
    		$table->bigInteger('constellation_id')->index();
    		$table->bigInteger('region_id')->index();
    		$table->string('solar_system_name')->index();
    		$table->string('constellation_name')->index();
    		$table->string('region_name')->index();
    		$table->bigInteger('npc_kills')->index();
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
    	Schema::dropIfExists('npc_kills');
    }
}
