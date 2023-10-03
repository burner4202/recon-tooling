<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMaterialPricesAddSumToUpwellTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('upwell_rigs', function (Blueprint $table) {
            $table->json('item_prices');
            $table->json('sum_prices');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('upwell_rigs', function (Blueprint $table) {
         $table->json('item_prices');
         $table->json('sum_prices');
     });
    }
}
