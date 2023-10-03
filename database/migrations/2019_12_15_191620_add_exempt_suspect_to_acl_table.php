<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExemptSuspectToAclTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acl_characters', function (Blueprint $table) {
            $table->boolean('aclc_suspect');
            $table->boolean('aclc_exempt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('acl_characters', function (Blueprint $table) {
            $table->boolean('aclc_suspect');
            $table->boolean('aclc_exempt');
        });
    }
}
