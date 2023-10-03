<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAugswarmTrackingTable extends Migration
{


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('augswarm_tracking', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('at_character_id')->index();
            $table->bigInteger('at_solar_system_id');
            $table->datetime('at_last_login');
            $table->datetime('at_last_logout');
            $table->bigInteger('at_logins');
            $table->integer('at_online');
            $table->text('at_ship_name');
            $table->bigInteger('at_ship_type_id');
            $table->text('at_ship_type_id_name');
            $table->datetime('at_last_updated');
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
        Schema::dropIfExists('augswarm_tracking');
    }

}
