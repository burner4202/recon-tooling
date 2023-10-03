<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToRefinedMaterialsTable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
        Schema::table('refined_materials', function(Blueprint $table)
        {
            $table->index('type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('refined_materials', function (Blueprint $table)
        {
            $table->dropIndex(['type_id']);
        });
    }
}
