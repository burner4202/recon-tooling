<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAclCharactersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acl_characters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('aclc_hash');
            $table->string('aclc_acl_hash');
            $table->string('aclc_character_name');
            $table->bigInteger('aclc_character_id');
            $table->string('aclc_corporation_name');
            $table->bigInteger('aclc_corporation_id');
            $table->string('aclc_alliance_name');
            $table->bigInteger('aclc_alliance_id');
            $table->string('aclc_gice_name');
            $table->string('aclc_state');

            # There are a number of actions on the ACL
            # added
            # remove # This is if they are a manager.
            # removed
            # changed
            # created # We will use this for the hash.

            $table->string('aclc_role');
            $table->datetime('aclc_action_date');
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
        Schema::dropIfExists('acl_characters');
    }
}
