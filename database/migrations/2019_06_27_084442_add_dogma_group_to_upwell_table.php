<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDogmaGroupToUpwellTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::table('upwell_modules', function (Blueprint $table) {
    		$table->text('upm_description');
    		$table->json('upm_dogma_attributes');
    		$table->json('upm_dogma_effects');
    		$table->integer('group_id');
    		$table->integer('market_group_id');
    		$table->string('slot');
    	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	Schema::table('upwell_modules', function (Blueprint $table) {
    		$table->text('upm_description');
    		$table->json('upm_dogma_attributes');
    		$table->json('upm_dogma_effects');
    		$table->integer('group_id');
    		$table->integer('market_group_id');
    		$table->string('slot');
    	});
    }
}
