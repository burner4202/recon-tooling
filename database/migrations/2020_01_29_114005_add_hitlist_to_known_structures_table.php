<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHitlistToKnownStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('known_structures', function (Blueprint $table) {
            $table->boolean('str_hitlist');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('known_structures', function (Blueprint $table) {
         $table->boolean('str_hitlist');
     });
    }
}
