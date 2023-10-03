<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IndexUpdatedAtColumnOnIndexesTable extends Migration
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
        	$table->index('updated_at');

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
        	$table->dropIndex(['updated_at']);

        });
    }
}
