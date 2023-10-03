<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdmWatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adm_watch', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('adm_system_id');
            $table->string('adm_system_name')->index();
            $table->integer('adm_constellation_id');
            $table->string('adm_constellation_name')->index();
            $table->integer('adm_region_id');
            $table->string('adm_region_name')->index();
            $table->integer('adm_state');
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
        Schema::dropIfExists('adm_watch');
    }
}
