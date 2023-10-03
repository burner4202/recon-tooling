<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChracterReportingLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('character_reporting', function (Blueprint $table) {

            $table->increments('id');
            $table->bigInteger('character_id');
            $table->string('character_name')->index();
            $table->bigInteger('corporation_id');
            $table->string('corporation_name')->index();
            $table->integer('system_id');
            $table->string('system_name')->index();
            $table->integer('constellation_id');
            $table->string('constellation_name')->index();
            $table->integer('region_id');
            $table->string('region_name')->index();
            $table->bigInteger('alliance_id');
            $table->string('alliance_name')->index();
            $table->string('hull_type')->index();
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
        Schema::dropIfExists('character_reporting');
    }
}

