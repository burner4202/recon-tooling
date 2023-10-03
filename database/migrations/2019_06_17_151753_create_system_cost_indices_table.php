<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemCostIndicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_cost_indices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sci_key');
            $table->bigInteger('sci_solar_system_id');
			$table->string('sci_solar_system_name');
			$table->bigInteger('sci_solar_constellation_id');
			$table->string('sci_solar_constellation_name');
			$table->bigInteger('sci_solar_region_id');
			$table->string('sci_solar_region_name');
			$table->float('sci_manufacturing', 10, 5);
			$table->float('sci_researching_time_efficiency', 10, 5);
			$table->float('sci_researching_material_efficiency', 10, 5);
			$table->float('sci_copying', 10, 5);
			$table->float('sci_invention', 10, 5);
			$table->float('sci_reaction', 10, 5);
			$table->date('sci_date');
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
        Schema::dropIfExists('system_cost_indices');
    }
}
