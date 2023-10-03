<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAllianceToStructureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('known_structures', function (Blueprint $table) {
            $table->bigInteger('str_owner_alliance_id');
            $table->string('str_owner_alliance_name');
            $table->string('str_status');
            $table->string('str_has_no_fitting');
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
            $table->bigInteger('str_owner_alliance_id');
            $table->string('str_owner_alliance_name');
            $table->string('str_status');
            $table->string('str_has_no_fitting');
        });
    }
}
