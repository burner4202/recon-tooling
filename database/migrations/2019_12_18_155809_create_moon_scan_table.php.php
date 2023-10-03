<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoonScanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('moon_scans', function (Blueprint $table) {
    		$table->increments('id');
    		$table->text('moon_hash');
    		$table->bigInteger('moon_id');
    		$table->string('moon_name');
    		$table->bigInteger('moon_system_id');
    		$table->string('moon_system_name');
    		$table->string('moon_product');
    		$table->float('moon_quantity', 20, 4);
    		$table->bigInteger('moon_ore_type_id');
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
    	Schema::dropIfExists('moon_scans');
    }
}

