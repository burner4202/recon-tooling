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

class CreateShipLocationOnlineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ship_location_online', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('slo_user_id');
            $table->bigInteger('slo_character_id');
            $table->text('slo_character_name');
            $table->bigInteger('slo_corporation_id');
            $table->text('slo_corporation_name');
            $table->bigInteger('slo_solar_system_id');
            $table->text('slo_solar_system_name');
            $table->bigInteger('slo_region_id');
            $table->text('slo_region_name');
            $table->bigInteger('slo_station_id');
            $table->text('slo_station_name'); 
            $table->bigInteger('slo_structure_id');
            $table->text('slo_structure_name');
            $table->date('slo_last_login');
            $table->date('slo_last_logout');
            $table->bigInteger('slo_logins');
            $table->integer('slo_online');
            $table->text('slo_ship_name');
            $table->bigInteger('slo_ship_type_id');
            $table->text('slo_ship_type_id_name');
            $table->bigInteger('slo_desto_solar_system_id');
            $table->text('slo_desto_solar_system_name');
            $table->bigInteger('slo_desto_solar_system_jumps');
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
        Schema::dropIfExists('ship_location_online');
    }
}


