<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSecurityStatusToIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::table('system_cost_indices', function (Blueprint $table) {
    		$table->decimal('sci_security_status', 8,2);
    
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
    		$table->decimal('sci_security_status', 8,2);
  
    	});
    }
}
