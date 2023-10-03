<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupDossierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_dossier', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dossier_title')->nullable();
            $table->bigInteger('corporation_id')->index();
            $table->string('corporation_name')->index();
            $table->bigInteger('alliance_id')->index()->nullable();
            $table->string('alliance_name')->index()->nullable();
            $table->json('structures')->nullable();
            $table->json('indexes')->nullable();
            $table->string('is_shell_corporation');
            $table->bigInteger('target_alliance_id')->index();
            $table->string('target_alliance_name')->index();
            $table->string('has_relationship_via_evewho_history');
            $table->string('has_office_in_alliance_staging');
            $table->string('has_related_killboard_activity');
            $table->string('presence_of_cyno_alts');
            $table->string('presence_of_freighter_alts');
            $table->string('locators_confirm_location_of_related_alliance');
            $table->string('has_structures_in_related_system_of_target_alliance');
            $table->string('has_structures_in_systems_with_very_high_indexes');
            $table->string('has_structures_on_expensive_money_moons');
            $table->boolean('intelligence_confirmed');
            $table->float('relationship_score', 5, 2);
            $table->string('corporation_function');
            $table->integer('created_by_user_id');
            $table->string('created_by_username');
            $table->integer('state');
            $table->integer('approved_by_user_id');
            $table->string('approved_by_username');
            $table->datetime('approved_date');
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('group_dossier');
    }
}
