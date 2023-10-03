<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add56DayToNewMoonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::table('new_moons', function (Blueprint $table) {
    		$table->float('moon_value_56_day', 20,2)->index();

    	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	Schema::table('new_moons', function (Blueprint $table) {
    		$table->float('moon_value_56_day', 20,2)->index();
    	});
    }
}
