<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;


class EntosisScouts extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */

    use Sortable;
    protected $table = 'entosis_scouts';

    protected $fillable = [
    	'es_campaign_id',
    	'es_target_system',
    	'es_user_id',
    	'es_username',
    	'es_character_id',
    	'es_character_name',
    	'es_character_alliance_id',
    	'es_character_alliance_name',
    	'es_location_system_id',
    	'es_location_system_name',
    	'es_ship_type_id',
    	'es_ship_type_name',
    	'es_registered_at',
    	'es_status',
    	'updated_at',
    	'created_at'
    ];
}

