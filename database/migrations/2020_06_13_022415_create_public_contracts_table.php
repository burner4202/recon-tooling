<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('contract_id')->index();
            $table->integer('type_id')->index();
            $table->string('type_name');
            $table->integer('region_id');
            $table->string('region_name')->index();
            $table->float('price', 100, 2);
            $table->string('date_issued');
            $table->string('date_expired');
            $table->string('issuer_id');
            $table->string('character_name')->nullable();
            $table->string('corporation_id');
            $table->string('corporation_name')->index();
            $table->string('alliance_id')->nullable();
            $table->string('alliance_name')->nullable()->index();
            $table->text('showinfo_link');
            $table->boolean('is_carrier')->index();
            $table->boolean('is_fax')->index();
            $table->boolean('is_dread')->index();
            $table->boolean('is_super')->index();
            $table->boolean('is_titan')->index();
            $table->boolean('is_npc_delve')->index();
            $table->json('contract_info');
            $table->float('standing', 5, 2)->index();
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
        Schema::dropIfExists('public_contracts');
    }
}
