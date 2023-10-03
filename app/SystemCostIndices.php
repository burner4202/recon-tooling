<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class SystemCostIndices extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
    */

    use Sortable;
    protected $table = 'system_cost_indices';

    protected $fillable = [
    	'sci_key',
    	'sci_solar_system_id',
    	'sci_solar_system_name',
    	'sci_solar_constellation_id',
    	'sci_solar_constellation_name',
    	'sci_solar_region_id',
    	'sci_solar_region_name',
    	'sci_manufacturing',
    	'sci_researching_time_efficiency',
    	'sci_researching_material_efficiency',
    	'sci_copying',
    	'sci_invention',
    	'sci_reaction',
    	'sci_date',
    	'sci_security_status',
    	'sci_manufacturing_delta',
    	'sci_researching_time_efficiency_delta',
    	'sci_researching_material_efficiency_delta',
    	'sci_copying_delta',
    	'sci_invention_delta',
    	'sci_reaction_delta',

    ];

    public $sortable = [
    	'sci_key',
    	'sci_solar_system_id',
    	'sci_solar_system_name',
    	'sci_solar_constellation_id',
    	'sci_solar_constellation_name',
    	'sci_solar_region_id',
    	'sci_solar_region_name',
    	'sci_manufacturing',
    	'sci_researching_time_efficiency',
    	'sci_researching_material_efficiency',
    	'sci_copying',
    	'sci_invention',
    	'sci_reaction',
    	'sci_date',
    	'sci_security_status',
    	'sci_manufacturing_delta',
    	'sci_researching_time_efficiency_delta',
    	'sci_researching_material_efficiency_delta',
    	'sci_copying_delta',
    	'sci_invention_delta',
    	'sci_reaction_delta',
    ];

}

