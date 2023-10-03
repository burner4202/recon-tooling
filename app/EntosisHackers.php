<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class EntosisHackers extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */

    use Sortable;
    protected $table = 'entosis_hackers';

    protected $fillable = [
    	'eh_campaign_id',
    	'eh_target_system',
    	'eh_user_id',
    	'eh_username',
    	'eh_character_id',
    	'eh_character_name',
    	'eh_character_alliance_id',
    	'eh_character_alliance_name',
    	'eh_location_system_id',
    	'eh_location_system_name',
    	'eh_ship_type_id',
    	'eh_ship_type_name',
    	'eh_registered_at',
    	'eh_status',
    	'updated_at',
    	'created_at'
    ];
}

