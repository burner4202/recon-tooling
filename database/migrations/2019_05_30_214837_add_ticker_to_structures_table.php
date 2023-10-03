<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTickerToStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('known_structures', function (Blueprint $table) {
            $table->string('str_owner_alliance_ticker');
            $table->bigInteger('str_region_id');
            $table->string('str_region_name');
  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('known_structures', function (Blueprint $table) {
           $table->string('str_owner_alliance_ticker');
            $table->bigInteger('str_region_id');
            $table->string('str_region_name');
        });
    }
}
