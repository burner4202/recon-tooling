<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToValueMoonTable extends Migration
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
    		$table->index('moon_value_24_hour');
    		$table->index('moon_value_7_day');
    		$table->index('moon_value_30_day');

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
    		$table->dropIndex(['moon_value_24_hour']);
    		$table->dropIndex(['moon_value_7_day']);
    		$table->dropIndex(['moon_value_30_day']);

    	});
    }
}


