<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRouteLoggingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('route_logging', function (Blueprint $table) {
    		$table->increments('id');
    		$table->integer('user_id');
    		$table->string('username')->index();
    		$table->string('ip')->index();
    		$table->string('url');
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
    	Schema::dropIfExists('route_logging');
    }
}
