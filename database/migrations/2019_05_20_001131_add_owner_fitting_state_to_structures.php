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

class AddOwnerFittingStateToStructures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('known_structures', function (Blueprint $table) {
            $table->string('str_system');
            $table->bigInteger('str_structure_id');
            $table->bigInteger('str_owner_corporation_id');
            $table->text('str_owner_corporation_name');
            $table->json('str_fitting');
            $table->float('str_value', 100);
            $table->string('str_state');
            $table->integer('str_destroyed');

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
            $table->string('system');
            $table->bigInteger('structure_id');
            $table->integer('owner_id');
            $table->text('owner');
            $table->json('fitting');
            $table->float('value');
            $table->string('state');
            $table->integer('destroyed');
        });
    }
}
