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

class ScoutLocationShipOnline extends Model
{
     /**
     * The database table used by the model.
     *
     * @var string
     */

     use Sortable;
     
     protected $table = 'ship_location_online';

     protected $fillable = [
     	'slo_user_id',
     	'slo_character_id',
     	'slo_character_name',
          'slo_corporation_id',
          'slo_corporation_name',
          'slo_solar_system_id',
          'slo_solar_system_name',
          'slo_region_id',
          'slo_region_name',
          'slo_station_id',
          'slo_station_name',
          'slo_structure_id',
          'slo_structure_name',
          'slo_last_login',
          'slo_last_logout',
          'slo_logins',
          'slo_online',
          'slo_ship_name',
          'slo_ship_type_id',
          'slo_ship_type_id_name',
          'slo_desto_solar_system_id',
          'slo_desto_solar_system_name',
          'slo_desto_solar_system_jumps',
          'slo_active',

     ];

     public $sortable = [ 
     	'slo_user_id',
     	'slo_character_name',
        'slo_corporation_name',
     	'slo_solar_system_name',
     	'slo_region_name',
     	'slo_station_name',
     	'slo_structure_name',
     	'slo_last_login',
     	'slo_last_logout',
     	'slo_logins',
     	'slo_online',
     	'slo_ship_name',
     	'slo_ship_type_id_name',
     	'slo_desto_solar_system_id',
     	'slo_desto_solar_system_name',
     	'slo_desto_solar_system_name',
     	'slo_desto_solar_system_jumps',
     	'slo_active',
     ];

}
