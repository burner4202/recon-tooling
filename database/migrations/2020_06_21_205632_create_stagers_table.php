<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stagers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('solar_system_id');
            $table->string('solar_system_name')->index();
            $table->integer('constellation_id');
            $table->string('constellation_name')->index();
            $table->integer('region_id');
            $table->string('region_name')->index();
            $table->integer('alliance_id')->index();
            $table->string('alliance_name')->index();
            $table->string('alliance_ticker');
            $table->float('standing', 5, 2)->index();
            $table->string('tag');
            $table->integer('created_by_user_id');
            $table->string('created_by_user_username');
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
        Schema::dropIfExists('stagers');
    }
}