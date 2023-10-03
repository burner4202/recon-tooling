<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

use Kyslik\ColumnSortable\Sortable;

class SovStructures extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */

    use Sortable;

    protected $table = 'sov_structures';

    protected $fillable = ['sov_structure_key', 'alliance_id', 'alliance_name', 'alliance_ticker', 'solar_system_id', 'solar_system_name', 'constellation_id', 'constellation_name', 'region_id', 'region_name', 'structure_type_id', 'structure_type_name', 'vulnerability_occupancy_level', 'vulnerable_end_time', 'vulnerable_start_time', 'supers_in_system', 'bridge_in_system', 'keepstar_in_system'];

    public $sortable = ['sov_structure_key', 'alliance_id', 'alliance_name', 'alliance_ticker', 'solar_system_id', 'solar_system_name', 'constellation_id', 'constellation_name', 'region_id', 'region_name', 'structure_type_id', 'structure_type_name', 'vulnerability_occupancy_level', 'vulnerable_end_time', 'vulnerable_start_time', 'supers_in_system', 'bridge_in_system', 'keepstar_in_system'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
    	'vulnerable_end_time',
    	'vulnerable_start_time',
    ];

}
