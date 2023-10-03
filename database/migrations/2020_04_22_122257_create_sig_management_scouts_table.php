<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSigManagementScoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sig_management_scouts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('name')->index();
            $table->string('check_in');
            $table->boolean('active')->index();
            $table->boolean('registered_on_rt')->index();
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
        Schema::dropIfExists('sig_management_scouts');
    }


}
