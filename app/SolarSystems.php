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



class SolarSystems extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */

    use Sortable;
    protected $table = 'solar_system';

    protected $fillable = ['ss_system_id', 'ss_system_name', 'ss_security_class', 'ss_security_status', 'ss_constellation_id', 'ss_constellation_name', 'ss_region_id', 'ss_region_name', 'ss_position', 'ss_stargates', 'ss_empty'];


    public $sortable = ['ss_region_name', 'ss_system_name', 'ss_constellation_name', 'ss_empty'];

    public function structures() 
    {
    	return $this->hasMany('Vanguard\KnownStructures');
    }


}
