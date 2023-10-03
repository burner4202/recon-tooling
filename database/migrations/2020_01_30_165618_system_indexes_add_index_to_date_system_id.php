<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SystemIndexesAddIndexToDateSystemId extends Migration
{
	 /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_cost_indices', function(Blueprint $table)
        {
            $table->index('sci_date');
            $table->index('sci_solar_system_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_cost_indices', function (Blueprint $table)
        {
            $table->dropIndex(['sci_date']);
            $table->dropIndex(['sci_solar_system_id']);
        });
    }
}
