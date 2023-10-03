<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_calls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('apc_ip_address');
            $table->text('apc_user_agent');
            $table->string('apc_endpoint');
            $table->string('apc_response');
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
        Schema::dropIfExists('api_calls');
    }
}
