<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewMoonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('new_moons', function (Blueprint $table) {
    		$table->increments('id');
    		$table->bigInteger('moon_id')->index();
    		$table->string('moon_name')->index();
    		$table->bigInteger('moon_system_id');
    		$table->string('moon_system_name')->index();
    		$table->bigInteger('moon_constellation_id');
    		$table->string('moon_constellation_name')->index();
    		$table->bigInteger('moon_region_id');
    		$table->string('moon_region_name')->index();
    		$table->string('moon_r_rating')->index();
    		$table->json('moon_dist_ore');
    		$table->json('moon_extraction_values');
    		$table->json('moon_ore_refine_value');
    		$table->float('moon_value_24_hour', 20,2)->index();
    		$table->float('moon_value_7_day', 20,2)->index();
    		$table->float('moon_value_30_day', 20,2)->index();
    		$table->boolean('moon_atmo_gases');
    		$table->boolean('moon_cadmium');
    		$table->boolean('moon_caesium');
    		$table->boolean('moon_chromium');
    		$table->boolean('moon_cobalt');
    		$table->boolean('moon_dysprosium');
    		$table->boolean('moon_eva_depo');
    		$table->boolean('moon_hafnium');
    		$table->boolean('moon_hydrocarbons');
    		$table->boolean('moon_mercury');
    		$table->boolean('moon_neodymium');
    		$table->boolean('moon_platinum');
    		$table->boolean('moon_promethium');
    		$table->boolean('moon_scandium');
    		$table->boolean('moon_silicates');
    		$table->boolean('moon_technetium');
    		$table->boolean('moon_thulium');
    		$table->boolean('moon_titanium');
    		$table->boolean('moon_tungsten');
    		$table->boolean('moon_vanadium');
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
    	Schema::dropIfExists('new_moons');
    }
}
