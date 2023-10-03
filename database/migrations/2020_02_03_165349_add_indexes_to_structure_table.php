<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToStructureTable extends Migration
{
	 /**
     * Run the migrations.
     *
     * @return void
     */
	 public function up()
	 {
	 	Schema::table('known_structures', function(Blueprint $table)
	 	{
	 		$table->index('str_destroyed');
	 		$table->index('str_type');
	 		$table->index('str_value');
	 		$table->index('str_owner_alliance_name');
	 		$table->index('str_state');
	 		$table->index('str_t2_rigged');
	 		$table->index('str_capital_shipyard');
	 		$table->index('str_supercapital_shipyard');

	 	});
	 }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	Schema::table('known_structures', function (Blueprint $table)
    	{
    		$table->dropIndex(['str_destroyed']);
    		$table->dropIndex(['str_type']);
    		$table->dropIndex(['str_value']);
    		$table->dropIndex(['str_owner_alliance_name']);
    		$table->dropIndex(['str_state']);
    		$table->dropIndex(['str_t2_rigged']);
    		$table->dropIndex(['str_capital_shipyard']);
    		$table->dropIndex(['str_supercapital_shipyard']);

    	});
    }
}
