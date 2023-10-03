<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoonCompareTable extends Migration
{
 /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('moon_compare', function (Blueprint $table) {
    		$table->increments('id');
    		$table->bigInteger('moon_id');
    		$table->string('moon_name')->index();
    		$table->bigInteger('moon_system_id');
    		$table->string('moon_system_name')->index();
    		$table->bigInteger('moon_constellation_id');
    		$table->string('moon_constellation_name')->index();
    		$table->bigInteger('moon_region_id');
    		$table->string('moon_region_name')->index();
    		$table->integer('moon_old_r_rating')->index();
    		$table->integer('moon_new_r_rating')->index();
    		$table->float('moon_old_value_56_day', 20,2)->index();
    		$table->float('moon_new_value_56_day', 20,2)->index();
    		$table->float('moon_percentage_difference', 10,2)->index();
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
    	Schema::dropIfExists('moon_compare');
    }
}
