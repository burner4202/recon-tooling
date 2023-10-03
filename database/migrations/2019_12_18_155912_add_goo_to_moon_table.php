<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGooToMoonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('moons', function (Blueprint $table) {
            $table->boolean('moon_atmo_gases');
            $table->boolean('moon_cadmium');
            $table->boolean('moon_caesium');
            $table->boolean('moon_chromium');
            $table->boolean('moon_cobalt');
            $table->boolean('moon_dysprosium');
            $table->boolean('moon_eva_depo');
            $table->boolean('moon_hafnium');
            $table->boolean('moon_hydrocarbons');
            $table->boolean('moon_mercury');
            $table->boolean('moon_neodymium');
            $table->boolean('moon_platinum');
            $table->boolean('moon_promethium');
            $table->boolean('moon_scandium');
            $table->boolean('moon_silicates');
            $table->boolean('moon_technetium');
            $table->boolean('moon_thulium');
            $table->boolean('moon_titanium');
            $table->boolean('moon_tungsten');
            $table->boolean('moon_vanadium');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('moons', function (Blueprint $table) {
            $table->boolean('moon_atmo_gases');
            $table->boolean('moon_cadmium');
            $table->boolean('moon_caesium');
            $table->boolean('moon_chromium');
            $table->boolean('moon_cobalt');
            $table->boolean('moon_dysprosium');
            $table->boolean('moon_eva_depo');
            $table->boolean('moon_hafnium');
            $table->boolean('moon_hydrocarbons');
            $table->boolean('moon_mercury');
            $table->boolean('moon_neodymium');
            $table->boolean('moon_platinum');
            $table->boolean('moon_promethium');
            $table->boolean('moon_scandium');
            $table->boolean('moon_silicates');
            $table->boolean('moon_technetium');
            $table->boolean('moon_thulium');
            $table->boolean('moon_titanium');
            $table->boolean('moon_tungsten');
            $table->boolean('moon_vanadium');
        });
    }
}
