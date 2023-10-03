<?php

/*
 * Goonswarm Federation Recon Tools
 *
 * Developed by scopehone <scopeh@gmail.com>
 * In conjuction with Natalya Spaghet & Mindstar Technology 
 *
 */


namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class EntosisCampaigns extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */

    use Sortable;

    protected $table = 'entosis_campaign';

    protected $fillable = [
    	'ec_campaign_id', 
    	'ec_target_system',
    	'ec_target_system_id',
    	'ec_target_constellation',
    	'ec_target_constellation_id',
    	'ec_target_region',
    	'ec_target_region_id',
    	'ec_target_adm',
    	'ec_event_type',
    	'ec_structure_type',
    	'ec_structure_type_id',
    	'ec_availability',
    	'ec_notes',
    	'ec_campaign_start_time',
    	'ec_structure_vulnerable_start_time',
    	'ec_structure_vulnerable_end_time',
    	'ec_campaign_created_by',
    	'ec_campaign_dispatched_by',
    	'ec_campaign_created_at',
    	'ec_campaign_dispatched_at',
    	'ec_status',
    	'ec_sov_attacker_score',
    	'ec_sov_defender_score',
    	'ec_sov_defender_id',
    	'ec_sov_structure_id'
    ];

    public $sortable = [
    	'ec_campaign_id', 
    	'ec_target_system',
    	'ec_target_system_id',
    	'ec_target_constellation',
    	'ec_target_constellation_id',
    	'ec_target_region',
    	'ec_target_region_id',
    	'ec_target_adm',
    	'ec_event_type',
    	'ec_structure_type',
    	'ec_structure_type_id',
    	'ec_availability',
    	'ec_notes',
    	'ec_campaign_start_time',
    	'ec_structure_vulnerable_start_time',
    	'ec_structure_vulnerable_end_time',
    	'ec_campaign_created_by',
    	'ec_campaign_dispatched_by',
    	'ec_campaign_created_at',
    	'ec_campaign_dispatched_at',
    	'ec_status',
    	'ec_sov_attacker_score',
    	'ec_sov_defender_score',
    	'ec_sov_defender_id',
    	'ec_sov_structure_id',
    	'updated_at'
    ];

}



