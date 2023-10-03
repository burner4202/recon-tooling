<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToMoonTable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('moons', function(Blueprint $table)
        {
            $table->index('moon_name');
            $table->index('moon_system_name');
            $table->index('moon_region_name');
            $table->index('moon_constellation_name');
            $table->index('moon_r_rating');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('moons', function (Blueprint $table)
        {
            $table->dropIndex(['moon_name']);
            $table->dropIndex(['moon_system_name']);
            $table->dropIndex(['moon_region_name']);
            $table->dropIndex(['moon_constellation_name']);
            $table->dropIndex(['moon_r_rating']);
        });
    }
}
