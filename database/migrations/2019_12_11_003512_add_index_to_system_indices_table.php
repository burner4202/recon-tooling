<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToSystemIndicesTable extends Migration
{
 /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_cost_indices', function(Blueprint $table)
        {
            $table->index('sci_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_cost_indices', function (Blueprint $table)
        {
            $table->dropIndex(['sci_key']);
        });
    }

}
