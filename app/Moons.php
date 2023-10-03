<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

use Kyslik\ColumnSortable\Sortable;

class Moons extends Model
{

	use Sortable;

	/**
     * The database table used by the model.
     *
     * @var string
     */

	protected $table = 'moons';

	protected $fillable = ['moon_id', 'moon_name', 'moon_system_id', 'moon_system_name', 'moon_constellation_id', 'moon_constellation_name', 'moon_region_id', 'moon_region_name', 'moon_r_rating', 'moon_dist_ore', 'moon_ore_refine', 'moon_ore_refine_value', 'moon_value_24_hour', 'moon_value_7_day', 'moon_value_30_day', 'moon_atmo_gases', 'moon_cadmium', 'moon_caesium', 'moon_chromium', 'moon_cobalt', 'moon_dysprosium', 'moon_eva_depo', 'moon_hafnium', 'moon_hydrocarbons', 'moon_mercury', 'moon_neodymium', 'moon_platinum', 'moon_promethium', 'moon_scandium', 'moon_silicates', 'moon_technetium', 'moon_thulium', 'moon_tungsten', 'moon_vanadium'];

	protected $sortable = ['moon_id', 'moon_name', 'moon_system_id', 'moon_system_name', 'moon_constellation_id', 'moon_constellation_name', 'moon_region_id', 'moon_region_name', 'moon_r_rating', 'moon_dist_ore', 'moon_ore_refine', 'moon_ore_refine_value', 'moon_value_24_hour', 'moon_value_7_day', 'moon_value_30_day', 'updated_at'];
}


