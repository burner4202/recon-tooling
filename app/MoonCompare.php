<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

use Kyslik\ColumnSortable\Sortable;

class MoonCompare extends Model
{
	use Sortable;

	/**
     * The database table used by the model.
     *
     * @var string
     */

	protected $table = 'moon_compare';

	protected $fillable = ['moon_id', 'moon_name', 'moon_system_id', 'moon_system_name', 'moon_constellation_id', 'moon_constellation_name', 'moon_region_id', 'moon_region_name', 'moon_old_r_rating', 'moon_new_r_rating', 'moon_old_value_56_day', 'moon_new_value_56_day', 'moon_percentage_difference'] ;

	protected $sortable = ['moon_id', 'moon_name', 'moon_system_id', 'moon_system_name', 'moon_constellation_id', 'moon_constellation_name', 'moon_region_id', 'moon_region_name', 'moon_old_r_rating', 'moon_new_r_rating', 'moon_old_value_56_day', 'moon_new_value_56_day', 'moon_percentage_difference'];
}
