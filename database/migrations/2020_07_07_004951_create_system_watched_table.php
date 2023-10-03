<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemWatchedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_watched', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('solar_system_id')->index();
            $table->string('solar_system_name')->index();
            $table->integer('constellation_id');
            $table->string('constellation_name');
            $table->integer('region_id');
            $table->string('region_name');
            $table->string('adash_url');
            $table->integer('local_numbers');
            $table->string('local_alliances', 500);
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
        Schema::dropIfExists('system_watched');
    }
}

