<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeMoonScanTableHashColumnType extends Migration
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
            $table->string('moon_hash')->change();
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
            $table->string('moon_hash');
        });
    }
}
