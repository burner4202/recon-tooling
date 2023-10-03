<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSovStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sov_structures', function (Blueprint $table) {

            $table->increments('id');
            $table->string('sov_structure_key')->index();
            $table->integer('alliance_id')->index()->unsigned();
            $table->string('alliance_name')->index();
            $table->string('alliance_ticker');
            $table->integer('solar_system_id')->index();
            $table->string('solar_system_name')->index();
            $table->integer('constellation_id')->index();
            $table->string('constellation_name')->index();
            $table->integer('region_id')->index();
            $table->string('region_name')->index();
            $table->integer('structure_type_id');
            $table->string('structure_type_name');
            $table->float('vulnerability_occupancy_level');
            $table->datetime('vulnerable_end_time');
            $table->datetime('vulnerable_start_time');
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
        Schema::dropIfExists('sov_structures');
    }
}
