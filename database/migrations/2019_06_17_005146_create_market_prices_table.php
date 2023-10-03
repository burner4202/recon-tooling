<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('market_id');
            $table->bigInteger('type_id');
            $table->string('date');
            $table->float('highest', 100);
            $table->float('lowest', 100);
             $table->float('average', 100);
            $table->integer('order_count');
            $table->integer('volume');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('market_prices');
    }
}
