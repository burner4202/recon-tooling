<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCharacterTableHulls extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::table('characters', function (Blueprint $table) {
    		$table->boolean('titan');
    		$table->boolean('faction_titan');
    		$table->boolean('super');
    		$table->boolean('faction_super');
    		$table->boolean('carrier');
    		$table->boolean('fax');
    		$table->boolean('dread');
    		$table->boolean('faction_dread');
    	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	Schema::table('characters', function (Blueprint $table) {
    		$table->boolean('titan');
    		$table->boolean('faction_titan');
    		$table->boolean('super');
    		$table->boolean('faction_super');
    		$table->boolean('carrier');
    		$table->boolean('fax');
    		$table->boolean('dread');
    		$table->boolean('faction_dread');
    	});
    }
}
