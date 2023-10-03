<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToAclcCharactersTable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acl_characters', function(Blueprint $table)
        {
            $table->index('aclc_hash');
            $table->index('aclc_character_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('acl_characters', function (Blueprint $table)
        {
            $table->dropIndex(['aclc_hash', 'aclc_character_name']);
        });
    }
}
