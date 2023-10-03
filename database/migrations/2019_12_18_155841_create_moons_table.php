<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('moons', function (Blueprint $table) {
    		$table->increments('id');
    		$table->bigInteger('moon_id');
    		$table->string('moon_name');
    		$table->bigInteger('moon_system_id');
    		$table->string('moon_system_name');
    		$table->bigInteger('moon_constellation_id');
    		$table->string('moon_constellation_name');
    		$table->bigInteger('moon_region_id');
    		$table->string('moon_region_name');
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
    	Schema::dropIfExists('moons');
    }
}

