<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class CoordActiveFleets extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'coord_active_fleets';

    protected $fillable = ['fleet_id', 'fc_character_name', 'fc_character_id', 'fc_system_id', 'fc_system_name', 'fc_constellation_id', 'fc_constellation_name', 'fc_region_id', 'fc_region_name', 'number_of_pilots', 'number_of_capsules', 'start_time', 'finish_time', 'duration', 'active'];
}
