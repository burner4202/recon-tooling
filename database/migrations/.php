<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePapLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pap_links', function (Blueprint $table) {
            $table->string('pap_id');
            $table->string('created_by');
            $table->string('fleet_id');
            $table->string('character_id');
            $table->string('character_name');
            $table->string('character_corp_id');
            $table->string('character_name');
            $table->string('ship_id');
            $table->string('ship_name');
            $table->string('joined_fleet');
            $table->string('role');
            $table->string('solar_system_id');
            $table->string('solar_system_name');
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
        Schema::dropIfExists('pap_links');
    }
}
