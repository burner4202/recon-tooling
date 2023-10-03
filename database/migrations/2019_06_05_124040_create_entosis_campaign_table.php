<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntosisCampaignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('entosis_campaign', function (Blueprint $table) {
    		$table->increments('id');
    		$table->string('ec_campaign_id');
    		$table->string('ec_target_system');
    		$table->bigInteger('ec_target_system_id');
    		$table->string('ec_target_constellation');
    		$table->bigInteger('ec_target_constellation_id');
    		$table->string('ec_target_region');
    		$table->bigInteger('ec_target_region_id');
    		$table->string('ec_event_type');
    		$table->string('ec_structure_type');
    		$table->string('ec_availability');
    		$table->text('ec_notes');
    		$table->string('ec_campaign_created_by');
    		$table->string('ec_campaign_dispatched_by');
    		$table->string('ec_campaign_finished_by');
    		$table->datetime('ec_campaign_created_at');
    		$table->datetime('ec_campaign_dispatched_at');
    		$table->datetime('ec_campaign_finished_at');
    		$table->integer('ec_status');

    		/* 0 = Pending ready for dispatch
    		 * 1 = Active Campaign
    		 * 2 = Completed Campaign
    		 */

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
    	Schema::dropIfExists('entosis_campaign');
    }
}

