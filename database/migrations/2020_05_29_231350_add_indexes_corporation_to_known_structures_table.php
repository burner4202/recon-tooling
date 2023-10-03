<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesCorporationToKnownStructuresTable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
        Schema::table('known_structures', function(Blueprint $table)
        {
            $table->string('str_owner_corporation_name')->change();
            $table->index('str_owner_corporation_name');
        });
     }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('known_structures', function (Blueprint $table)
        {
            $table->dropIndex(['str_owner_corporation_name']);
        });
    }
}
