<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IndexDeltasInSystemIndexesTable extends Migration
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
	 		$table->index('sci_manufacturing_delta');
	 		$table->index('sci_researching_time_efficiency_delta');
	 		$table->index('sci_copying_delta');
	 		$table->index('sci_invention_delta');
	 		$table->index('sci_reaction_delta');
	 		$table->index('sci_manufacturing');
	 		$table->index('sci_researching_time_efficiency');
	 		$table->index('sci_researching_material_efficiency');
	 		$table->index('sci_copying');
	 		$table->index('sci_invention');
	 		$table->index('sci_reaction');

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
    		$table->dropIndex(['sci_manufacturing_delta']);
    		$table->dropIndex(['sci_researching_time_efficiency_delta']);
    		$table->dropIndex(['sci_copying_delta']);
    		$table->dropIndex(['sci_invention_delta']);
    		$table->dropIndex(['sci_reaction_delta']);
    		$table->dropIndex(['sci_manufacturing']);
    		$table->dropIndex(['sci_researching_time_efficiency']);
    		$table->dropIndex(['sci_researching_material_efficiency']);
    		$table->dropIndex(['sci_copying']);
    		$table->dropIndex(['sci_invention']);
    		$table->dropIndex(['sci_reaction']);

    	});
    }
}


