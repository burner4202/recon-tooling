<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoonOreToMoonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::table('moons', function (Blueprint $table) {
    		$table->string('moon_r_rating');
    		$table->json('moon_dist_ore');
    		$table->json('moon_ore_refine');
    		$table->json('moon_ore_refine_value');
    		$table->float('moon_value_24_hour', 20,2);
    		$table->float('moon_value_7_day', 20,2);
    		$table->float('moon_value_30_day', 20,2);
    	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	Schema::table('moons', function (Blueprint $table) {
    		$table->string('moon_r_rating');
    		$table->json('moon_dist_ore');
    		$table->json('moon_ore_refine');
    		$table->json('moon_ore_refine_value');
    		$table->float('moon_value_24_hour', 20,2);
    		$table->float('moon_value_7_day', 20,2);
    		$table->float('moon_value_30_day', 20,2);
    	});
    }
}
