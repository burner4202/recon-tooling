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

class KnownStructures extends Model
{
       /**
     * The database table used by the model.
     *
     * @var string
     */

       use Sortable;
       
       protected $table = 'known_structures';
       
       protected $fillable = ['str_structure_id_md5', 'str_system_id', 'str_type_id', 'str_name', 'str_type', 'str_distance', 'str_system', 'str_structure_id', 'str_owner_corporation_id', 'str_owner_corporation_name', 'str_fitting', 'str_value', 'str_state', 'str_destroyed', 'str_owner_alliance_id', 'str_owner_alliance_name',  'str_status', 'str_has_no_fitting', 'str_owner_alliance_ticker', 'str_region_id', 'str_region_name', 'str_constellation_id', 'str_constellation_name', 'str_size', 'str_market', 'str_capital_shipyard', 'str_hyasyoda', 'str_invention', 'str_manufacturing', 'str_research', 'str_supercapital_shipyard', 'str_biochemical', 'str_hybrid', 'str_moon_drilling', 'str_reprocessing', 'str_point_defense', 'str_dooms_day', 'str_guide_bombs', 'str_anti_cap', 'str_anti_subcap', 'str_t2_rigged', 'str_cloning', 'str_composite', 'str_package_delivered', 'str_abandoned_time', 'str_cored', 'str_vertified_package', 'str_moon_rarity'];

       public $sortable = ['str_name', 'str_type', 'str_system', 'str_owner_corporation_name', 'str_value', 'str_state', 'str_destroyed', 'str_system_id', 'str_structure_id', 'created_at', 'updated_at', 'str_owner_alliance_name', 'str_status', 'str_has_no_fitting', 'str_owner_alliance_ticker', 'str_region_name', 'str_vul_day', 'str_vul_hour', 'str_constellation_id', 'str_constellation_name', 'str_size', 'str_market', 'str_capital_shipyard', 'str_hyasyoda', 'str_invention', 'str_manufacturing', 'str_research', 'str_supercapital_shipyard', 'str_biochemical', 'str_hybrid', 'str_moon_drilling', 'str_reprocessing', 'str_point_defense', 'str_dooms_day', 'str_guide_bombs', 'str_anti_cap', 'str_anti_subcap', 'str_t2_rigged', 'str_cloning','str_composite', 'str_package_delivered', 'str_abandoned_time', 'str_cored', 'str_vertified_package', 'str_moon_rarity'];

       public function corporations() 
       {
       	return $this->belongsTo('Vanguard\Corporations');
       }

       public function alliances() 
       {
       	return $this->belongsTo('Vanguard\Alliances');
       }

       public function system() 
       {
       	return $this->belongsTo('Vanguard\SolarSystems');
       }

       //public $timestamps = false;

    }
    

     