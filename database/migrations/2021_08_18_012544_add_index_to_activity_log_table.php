<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToActivityLogTable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
        Schema::table('activity_tracker', function(Blueprint $table)
        {
            $table->string('at_structure_hash', 255)->change()->index();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activity_tracker', function (Blueprint $table)
        {
            $table->dropIndex(['at_structure_hash']);

        });
    }
}
