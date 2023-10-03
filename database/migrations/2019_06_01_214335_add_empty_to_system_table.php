<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmptyToSystemTable extends Migration
{
 /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solar_system', function (Blueprint $table) {
            $table->string('ss_empty');
         
  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('solar_system', function (Blueprint $table) {
         $table->string('ss_empty');
      
        });
    }
}
