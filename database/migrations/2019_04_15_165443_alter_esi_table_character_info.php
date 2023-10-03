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

class AlterEsiTableCharacterInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('esi_tokens', function (Blueprint $table) {
            $table->string('esi_character_name');
            $table->integer('esi_corporation_id');
            $table->string('esi_corporation_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('esi_tokens', function (Blueprint $table) {
            $table->string('esi_character_name');
            $table->integer('esi_corporation_id');
            $table->string('esi_corporation_name');
        });
    }
}
