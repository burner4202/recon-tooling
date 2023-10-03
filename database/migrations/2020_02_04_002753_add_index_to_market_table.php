<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToMarketTable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
        Schema::table('market_prices', function(Blueprint $table)
        {
            $table->index('market_id');
            $table->index('type_id');
            $table->index('date');
            $table->index('average');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('market_prices', function (Blueprint $table)
        {
            $table->dropIndex(['market_id']);
            $table->dropIndex(['type_id']);
            $table->dropIndex(['date']);
            $table->dropIndex(['average']);
        });
    }
}
