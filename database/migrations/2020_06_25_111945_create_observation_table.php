<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObservationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('observations', function (Blueprint $table) {
    		$table->increments('id');
    		$table->string('unique_id')->index();
    		$table->text('observation');
    		$table->integer('created_by_user_id');
    		$table->string('created_by_username')->index();
    		$table->integer('state')->index();
    		$table->integer('prority')->index();
    		$table->string('tags')->nullable();
    		$table->integer('solar_system_id')->nullable();
    		$table->string('solar_system_name')->index()->nullable();
    		$table->integer('corporation_id')->index()->nullable();
    		$table->string('corporation_name')->index()->nullable();
    		$table->string('corporation_ticker')->nullable();
    		$table->integer('alliance_id')->index()->nullable();
    		$table->string('alliance_name')->index()->nullable();
    		$table->string('alliance_ticker')->nullable();
    		$table->float('score', 3, 2)->index();
    		$table->integer('reviewed_by_user_id')->nullable();
    		$table->string('reviewed_by_username')->nullable();
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
    	Schema::dropIfExists('observations');
    }
}

