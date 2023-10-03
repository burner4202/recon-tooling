<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAclTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        # ID / User Added / Total Character Count / Audit Time / Compliant Characters / Non Compliant Characters / JSON Column with RAW / JSON Column with Characters etc.
        Schema::create('acl', function (Blueprint $table) {
            $table->increments('id');
            $table->string('acl_hash');
            $table->string('acl_added_by');
            $table->string('acl_name');
            $table->string('acl_total_characters');
            $table->datetime('acl_created_time');
            $table->text('acl_raw');
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
        Schema::dropIfExists('acl');
    }
}
