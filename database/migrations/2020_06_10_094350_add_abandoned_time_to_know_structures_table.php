<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAbandonedTimeToKnowStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('known_structures', function (Blueprint $table) {
            $table->string('str_abandoned_time');

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
            $table->string('str_abandoned_time');
        });
    }
}
