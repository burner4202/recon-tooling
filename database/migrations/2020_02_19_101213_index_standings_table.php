<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IndexStandingsTable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
     	Schema::table('alliance_standings', function(Blueprint $table)
     	{
     		$table->index('as_contact_id');

     	});
     }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	Schema::table('alliance_standings', function (Blueprint $table)
    	{
    		$table->dropIndex(['as_contact_id']);

    	});
    } //

}
