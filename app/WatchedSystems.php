<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

use Kyslik\ColumnSortable\Sortable;

class WatchedSystems extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */

    use Sortable;

    protected $table = 'system_watched';

    protected $fillable = [
    	'solar_system_id', 
    	'solar_system_name',
    	'constellation_id',
    	'constellation_name',
    	'region_id',
    	'region_name',
    	'adash_url',
    	'local_numbers',
    	'local_alliances',
    	'created_at',
    	'updated_at'
    ];

    public $sortable = [
    	'solar_system_id', 
    	'solar_system_name',
    	'constellation_id',
    	'constellation_name',
    	'region_id',
    	'region_name',
    	'adash_url',
    	'local_numbers',
    	'local_alliances',
    	'created_at',
    	'updated_at'
    ];
}
