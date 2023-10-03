<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoalitionsAlliancesBakeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coalitions_alliances_bake', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('coalition_id');
          $table->string('coalition_name')->index();
          $table->integer('corporation_id');
          $table->string('corporation_name')->index();
          $table->integer('corporation_member_count');
          $table->integer('alliance_id');
          $table->string('alliance_name')->index();
          $table->string('alliance_ticker');
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
        Schema::dropIfExists('coalitions_alliances_bake');
    }
}
