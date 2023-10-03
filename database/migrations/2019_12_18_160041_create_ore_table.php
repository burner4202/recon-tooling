<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ore', function (Blueprint $table) {

            $table->increments('id');
            $table->string('name');
            $table->integer('type_id')->unsigned();
            $table->text('description');
            $table->integer('group_id');
            $table->integer('icon_id');
            $table->integer('portion_size');
            $table->json('ore_json');
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
        Schema::dropIfExists('ore');
    }
}
