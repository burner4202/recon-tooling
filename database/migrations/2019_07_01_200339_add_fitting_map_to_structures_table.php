<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFittingMapToStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('known_structures', function (Blueprint $table) {
            $table->boolean('str_market');
            $table->boolean('str_capital_shipyard');
            $table->boolean('str_hyasyoda');
            $table->boolean('str_invention');
            $table->boolean('str_manufacturing');
            $table->boolean('str_research');
            $table->boolean('str_supercapital_shipyard');
            $table->boolean('str_biochemical');
            $table->boolean('str_hybrid');
            $table->boolean('str_moon_drilling');
            $table->boolean('str_reprocessing');
            $table->boolean('str_point_defense');
            $table->boolean('str_dooms_day');
            $table->boolean('str_guide_bombs');
            $table->boolean('str_anti_cap');
            $table->boolean('str_anti_subcap');
            $table->boolean('str_t2_rigged');

            
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
           $table->boolean('str_market');
           $table->boolean('str_capital_shipyard');
           $table->boolean('str_hyasyoda');
           $table->boolean('str_invention');
           $table->boolean('str_manufacturing');
           $table->boolean('str_research');
           $table->boolean('str_supercapital_shipyard');
           $table->boolean('str_biochemical');
           $table->boolean('str_hybrid');
           $table->boolean('str_moon_drilling');
           $table->boolean('str_reprocessing');
           $table->boolean('str_point_defense');
           $table->boolean('str_dooms_day');
           $table->boolean('str_guide_bombs');
           $table->boolean('str_anti_cap');
           $table->boolean('str_anti_subcap');
           $table->boolean('str_t2_rigged');
       });
    }
}

