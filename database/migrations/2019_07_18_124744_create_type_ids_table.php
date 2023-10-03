<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTypeIdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('type_ids', function (Blueprint $table) {

    		$table->increments('id');
    		$table->bigInteger('ti_type_id');
    		$table->text('ti_name');
    		$table->text('ti_description');
    		$table->json('ti_dogma_attributes');
    		$table->json('ti_dogma_effects');
    		$table->bigInteger('ti_group_id');
    		$table->bigInteger('ti_market_group_id');
    		$table->string('ti_slot');
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
    	Schema::dropIfExists('type_ids');
    }
}