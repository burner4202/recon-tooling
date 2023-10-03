<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllianceHealthIndexTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alliance_health_index', function (Blueprint $table) {
          $table->increments('id');
            $table->string('key')->index();
            $table->bigInteger('alliance_id')->index();
            $table->string('alliance_name')->index();
            $table->string('alliance_ticker');
            $table->integer('ihub_count');
            $table->float('health', 5);
            $table->float('average_adm', 5);
            $table->date('date');
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
        Schema::dropIfExists('alliance_health_index');
    }
}

