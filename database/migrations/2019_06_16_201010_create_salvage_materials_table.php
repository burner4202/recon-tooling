<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalvageMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salvage_materials', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('type_id');
            $table->string('name');
            $table->text('description');
            $table->bigInteger('group_id');
            $table->bigInteger('icon_id');
            $table->float('volume', 3);
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
        Schema::dropIfExists('salvage_materials');
    }
}


