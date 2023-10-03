<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUpwellRigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upwell_rigs', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('type_id');
            $table->string('name');
            $table->text('description');
            $table->bigInteger('group_id');
            $table->bigInteger('icon_id');
            $table->json('meta_data');
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
        Schema::dropIfExists('upwell_rigs');
    }
}
