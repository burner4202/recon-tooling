<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFittingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('fittings', function (Blueprint $table) {
    		$table->increments('id');
    		$table->text('fitting_name');
    		$table->text('fitting_hull_name');
    		$table->bigInteger('fitting_hull_type_id');
    		$table->float('fitting_hull_value', 12, 2);
    		$table->json('fitting_modules');
    		$table->float('fitting_module_value', 12, 2);
    		$table->json('fitting_cargo');
    		$table->float('fitting_cargo_value', 12, 2);
    		$table->float('fitting_value', 12, 2);
    		$table->string('fitting_fitting_type');
    		$table->string('fitting_added_by');
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
    	Schema::dropIfExists('fittings');
    }
}
