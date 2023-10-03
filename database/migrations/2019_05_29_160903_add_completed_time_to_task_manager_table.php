<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompletedTimeToTaskManagerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task_manager', function (Blueprint $table) {
            $table->datetime('tm_completed_datetime_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task_manager', function (Blueprint $table) {
            $table->datetime('tm_completed_datetime_at');
        });
    }
}
