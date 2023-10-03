<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSupersBridgesToSovSystems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sov_structures', function (Blueprint $table) {
            $table->boolean('supers_in_system');
            $table->boolean('bridge_in_system');
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
         $table->boolean('supers_in_system');
         $table->boolean('bridge_in_system');
     });
    }
}
