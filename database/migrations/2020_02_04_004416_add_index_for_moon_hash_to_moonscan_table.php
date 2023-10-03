<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexForMoonHashToMoonscanTable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
        Schema::table('moon_scans', function(Blueprint $table)
        {
            $table->index('moon_hash');
            $table->index('moon_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('moon_scans', function (Blueprint $table)
        {
            $table->dropIndex(['moon_hash']);
            $table->dropIndex(['moon_name']);
        });
    }
}
