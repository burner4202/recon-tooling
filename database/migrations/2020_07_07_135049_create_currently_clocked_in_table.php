<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrentlyClockedInTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currently_clocked_in', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fleet_id')->index();
            $table->string('fleet_owner')->index();
            $table->string('fleet_boss');
            $table->integer('fleet_size');
            $table->string('freemove');
            $table->string('system_numbers', 1000);
            $table->string('hull_numbers', 1000);
            $table->boolean('active');
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
        Schema::dropIfExists('currently_clocked_in');
    }
}

