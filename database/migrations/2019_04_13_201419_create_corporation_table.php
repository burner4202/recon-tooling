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

class CreateCorporationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
           Schema::create('corporation', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('corporation_corporation_id')->unique();
            $table->unsignedInteger('corporation_alliance_id');
            $table->unsignedInteger('corporation_ceo_id');
            $table->unsignedInteger('corporation_creator_id');
            $table->string('corporation_date_founded');
            $table->string('corporation_description');
            $table->unsignedInteger('corporation_member_count');
            $table->string('corporation_name');
            $table->string('corporation_tax_rate');
            $table->string('corporation_ticker');
            $table->string('corporation_url');
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
        Schema::dropIfExists('corporation');
    }
}
