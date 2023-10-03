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

class CreateEsiTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('esi_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('esi_user_id');
            $table->string('esi_name');
            $table->unsignedInteger('esi_character_id');
            $table->string('esi_avatar');
            $table->text('esi_token');
            $table->text('esi_refresh_token');
            $table->string('esi_scopes');
            $table->string('esi_owner_hash');
            $table->integer('esi_active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('esi_tokens');
    }
}
