<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKillmailTable extends Migration
{

	/**
     * Run the migrations.
     *
     * @return void
     */
	public function up()
	{
		Schema::create('killmails', function (Blueprint $table) {
			$table->increments('id');
			$table->bigInteger('killmail_id');
			$table->json('data');
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
    	Schema::dropIfExists('killmails');
    }
}
