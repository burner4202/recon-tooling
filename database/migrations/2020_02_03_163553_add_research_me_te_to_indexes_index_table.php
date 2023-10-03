<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddResearchMeTeToIndexesIndexTable extends Migration
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
	 		$table->index('sci_researching_material_efficiency_delta', 'sci_researching_me_delta');
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
    		$table->dropIndex(['sci_researching_material_efficiency_delta'], 'sci_researching_me_delta');
    	});
    }
}
