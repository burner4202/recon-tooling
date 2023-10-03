<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoordActiveFleetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coord_active_fleets', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('fleet_id')->index();
            $table->string('fc_character_name')->index();
            $table->bigInteger('fc_character_id');
            $table->bigInteger('fc_system_id');
            $table->string('fc_system_name');
            $table->bigInteger('fc_constellation_id');
            $table->string('fc_constellation_name');
            $table->bigInteger('fc_region_id');
            $table->string('fc_region_name');
            $table->integer('number_of_pilots');
            $table->integer('number_of_capsules');
            $table->string('start_time');
            $table->string('finish_time');
            $table->float('duration', 2);
            $table->integer('active');
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
        Schema::dropIfExists('coord_active_fleets');
    }
}
