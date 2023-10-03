<?php

/*
 * Goonswarm Federation Recon Tools
 *
 * Developed by scopehone <scopeh@gmail.com>
 * In conjuction with Natalya Spaghet & Mindstar Technology 
 *
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStructureTableForAddedByScout extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::table('known_structures', function (Blueprint $table) {
    		$table->integer('str_added_by_id');
    		$table->string('str_added_by_user');
    		$table->integer('str_updated_by_id');
    		$table->string('str_updated_by_user');
    		

    	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	Schema::table('known_structures', function (Blueprint $table) {
    		$table->integer('added_by_id');
    		$table->string('added_by_user');
    		$table->integer('updated_by_id');
    		$table->string('updated_by_user');
    	});
    }
}

