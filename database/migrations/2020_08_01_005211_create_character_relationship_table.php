<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCharacterRelationshipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('character_relationship', function (Blueprint $table) {

            $table->increments('id');
            $table->bigInteger('character_id');
            $table->string('character_name')->index();
            $table->bigInteger('associated_character_id');
            $table->string('associated_character_name')->index();
            $table->bigInteger('associated_corporation_id');
            $table->string('associated_corporation_name')->index();
            $table->bigInteger('associated_alliance_id')->nullable();
            $table->string('associated_alliance_name')->nullable()->index();
            $table->text('notes')->nullable();
            $table->string('added_by');
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
        Schema::dropIfExists('character_relationship');
    }
}
