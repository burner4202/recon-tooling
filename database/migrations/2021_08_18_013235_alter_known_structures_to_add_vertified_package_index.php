<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterKnownStructuresToAddVertifiedPackageIndex extends Migration
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
            $table->index('str_vertified_package');

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
            $table->dropIndex(['str_vertified_package']);

        });
    }
}
