<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGsfUserToApiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::table('api_calls', function (Blueprint $table) {
    		$table->string('apc_gsf_username');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	Schema::table('api_calls', function (Blueprint $table) {
    		$table->string('apc_gsf_username');
        });
    }
}

