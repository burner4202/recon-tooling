<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCynoFreighterRorqualToCharacters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->boolean('industrial_cyno');
            $table->boolean('cyno');
            $table->boolean('freighter');
            $table->boolean('rorqual');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->boolean('industrial_cyno');
            $table->boolean('cyno');
            $table->boolean('freighter');
            $table->boolean('rorqual');
        });
    }
}
