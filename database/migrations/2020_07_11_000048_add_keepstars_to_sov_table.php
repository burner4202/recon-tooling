<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKeepstarsToSovTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sov_structures', function (Blueprint $table) {
            $table->boolean('keepstar_in_system');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sov_structures', function (Blueprint $table) {
            $table->boolean('keepstar_in_system');
        });
    }
}
