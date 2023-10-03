<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeltasToSystemIndicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::table('system_cost_indices', function (Blueprint $table) {
    		$table->float('sci_manufacturing_delta', 8, 2);
    		$table->float('sci_researching_time_efficiency_delta', 8, 2);
    		$table->float('sci_researching_material_efficiency_delta', 8, 2);
    		$table->float('sci_copying_delta', 8, 2);
    		$table->float('sci_invention_delta', 8, 2);
    		$table->float('sci_reaction_delta', 8, 2);
    	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	Schema::table('system_cost_indices', function (Blueprint $table) {
    		$table->float('sci_manufacturing_delta', 8, 2);
    		$table->float('sci_researching_time_efficiency_delta', 8, 2);
    		$table->float('sci_researching_material_efficiency_delta', 8, 2);
    		$table->float('sci_copying_delta', 8, 2);
    		$table->float('sci_invention_delta', 8, 2);
    		$table->float('sci_reaction_delta', 8, 2);
    	});
    }
}
