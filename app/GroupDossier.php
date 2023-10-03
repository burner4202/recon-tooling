<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class GroupDossier extends Model
{
        /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'group_dossier';

    protected $fillable = [
    	'dossier_title',
    	'corporation_id',
    	'corporation_name',
    	'alliance_id',
    	'alliance_name',
    	'structures',
    	'indexes',
    	'is_shell_corporation',
    	'target_alliance_id',
    	'target_alliance_name',
    	'has_relationship_via_evewho_history',
    	'has_office_in_alliance_staging',
    	'has_related_killboard_activity',
    	'presence_of_cyno_alts',
    	'presence_of_freighter_alts',
    	'locators_confirm_location_of_related_alliance',
    	'has_structures_in_related_system_of_target_alliance',
    	'has_structures_in_systems_with_very_high_indexes',
    	'has_structures_on_expensive_money_moons',
        'intelligence_confirmed',
        'relationship_score',
        'corporation_function',
        'created_by_user_id',
        'created_by_username',
        'state',
        'approved_by_user_id',
        'approved_by_username',
        'approved_date',
        'notes'
    ];
}
            