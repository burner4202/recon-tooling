<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToSystemIndicesTable extends Migration
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
            $table->index('sci_security_status');
            $table->index('sci_solar_region_name');

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
            $table->dropIndex(['sci_security_status']);
            $table->dropIndex(['sci_solar_region_name']);

        });
    }
}
