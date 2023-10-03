<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Vanguard\KnownStructures;
use Vanguard\SystemCostIndices;
use Vanguard\NewMoons;
use Carbon\Carbon;

class RegionalReportController extends Controller
{
	public function index() {

		$regions = KnownStructures::groupBy('str_region_name')->where('str_destroyed', 0)->get();
		//$structures_alive = KnownStructures::where('str_destroyed', 0)->get();
		//$oldest_structure = KnownStructures::where('str_destroyed', 0)->orderBy('updated_at', 'ASC')->get();

		return view('region.index', compact('regions'
		 //'structures_alive', 
		 //'oldest_structure'
	));
	}

	public function view($region_name) {

		$now = Carbon::now();
		$week_ago = Carbon::now()->subWeek(1);
		$today_format = $now->format('Y-m-d');

		$moons = NewMoons::where('moon_region_name', $region_name)
		->where('moon_value_56_day', '>', 1)
		->orderBy('moon_value_56_day', 'DESC')
		->get()
		->take(5);

		$structures = KnownStructures::where('str_region_name', $region_name)
		->where('str_destroyed', 0)
		->get();
		
		$alliances = KnownStructures::where('str_region_name', $region_name)
		->where('str_destroyed', 0)
		->groupBy('str_owner_alliance_name')
		->get();
		/*
		$corporations = KnownStructures::where('str_region_name', $region_name)
		->where('str_destroyed', 0)
		->groupBy('str_owner_corporation_name')
		->get();
		*/

		$keepstars = KnownStructures::where('str_region_name', $region_name)
		->where('str_destroyed', 0)
		->where('str_type', 'Keepstar')
		->orderBy('str_value', 'DESC')
		->take(5)
		->get();

		$sotiyos = KnownStructures::where('str_region_name', $region_name)
		->where('str_destroyed', 0)
		->where('str_type', 'Sotiyo')
		->orderBy('str_value', 'DESC')
		->take(5)
		->get();

		$azbels = KnownStructures::where('str_region_name', $region_name)
		->where('str_destroyed', 0)
		->where('str_type', 'Azbel')
		->orderBy('str_value', 'DESC')
		->take(5)
		->get();

		$fortizars = KnownStructures::where('str_region_name', $region_name)
		->where('str_destroyed', 0)
		->where('str_type', 'Fortizar')
		->orderBy('str_value', 'DESC')
		->take(5)
		->get();

		$online = KnownStructures::where('str_region_name', $region_name)
		->where('str_destroyed', 0)
		->where('str_state', 'High Power')
		->count();

		$manufacturing = SystemCostIndices::where('sci_solar_region_name', $region_name)
		->whereDate('updated_at', '=', $today_format)
		->where('sci_security_status', '<', '0.5')
		->orderBy('sci_manufacturing', 'DESC')
		->take(5)
		->get();

		$researching_te = SystemCostIndices::where('sci_solar_region_name', $region_name)
		->whereDate('updated_at', '=', $today_format)
		->where('sci_security_status', '<', '0.5')
		->orderBy('sci_researching_time_efficiency', 'DESC')
		->take(5)
		->get();

		$researching_me = SystemCostIndices::where('sci_solar_region_name', $region_name)
		->whereDate('updated_at', '=', $today_format)
		->where('sci_security_status', '<', '0.5')
		->orderBy('sci_researching_material_efficiency', 'DESC')
		->take(5)
		->get();

		$copying = SystemCostIndices::where('sci_solar_region_name', $region_name)
		->whereDate('updated_at', '=', $today_format)
		->where('sci_security_status', '<', '0.5')
		->orderBy('sci_copying', 'DESC')
		->take(5)
		->get();

		$invention = SystemCostIndices::where('sci_solar_region_name', $region_name)
		->whereDate('updated_at', '=', $today_format)
		->where('sci_security_status', '<', '0.5')
		->orderBy('sci_invention', 'DESC')
		->take(5)
		->get();

		$reactions = SystemCostIndices::where('sci_solar_region_name', $region_name)
		->whereDate('updated_at', '=', $today_format)
		->where('sci_security_status', '<', '0.5')
		->orderBy('sci_reaction', 'DESC')
		->take(5)
		->get();

		$capital_production = KnownStructures::where('str_region_name', $region_name)
		->where('str_destroyed', 0)
		->where('str_capital_shipyard', 1)
		->orderBy('str_value', 'DESC')
		->take(5)
		->get();

		$super_capital_production = KnownStructures::where('str_region_name', $region_name)
		->where('str_destroyed', 0)
		->where('str_supercapital_shipyard', 1)
		->orderBy('str_value', 'DESC')
		->take(5)
		->get();

		$t2_rigged = KnownStructures::where('str_region_name', $region_name)
		->where('str_destroyed', 0)
		->where('str_t2_rigged', 1)
		->orderBy('str_value', 'DESC')
		->take(15)
		->get();

		$jump_bridges = KnownStructures::where('str_region_name', $region_name)
		->where('str_destroyed', 0)
		->where('str_type', 'Ansiblex Jump Gate')
		->orderBy('str_name', 'ASC')
		->take(15)
		->get();

		$total_structures = KnownStructures::where('str_region_name', $region_name)
		->where('str_destroyed', 0)
		->count();

		$oldest_structure = KnownStructures::where('str_region_name', $region_name)
		->where('str_destroyed', 0)
		->orderBy('updated_at', 'ASC')
		->first();

		/*

		$manufacturing_delta_increase = SystemCostIndices::where('sci_solar_region_name', $region_name)
		->whereBetween('updated_at', [$week_ago, $now])
		->orderBy('sci_manufacturing_delta', 'DESC')
		->take(5)
		->get();

		$research_te_delta_increase = SystemCostIndices::where('sci_solar_region_name', $region_name)
		->whereBetween('updated_at', [$week_ago, $now])
		->orderBy('sci_researching_time_efficiency_delta', 'DESC')
		->take(5)
		->get();

		$research_me_delta_increase = SystemCostIndices::where('sci_solar_region_name', $region_name)
		->whereBetween('updated_at', [$week_ago, $now])
		->orderBy('sci_researching_material_efficiency_delta', 'DESC')
		->take(5)
		->get();

		$copying_delta_increase = SystemCostIndices::where('sci_solar_region_name', $region_name)
		->whereBetween('updated_at', [$week_ago, $now])
		->orderBy('sci_copying_delta', 'DESC')
		->take(5)
		->get();

		$invention_delta_increase = SystemCostIndices::where('sci_solar_region_name', $region_name)
		->whereBetween('updated_at', [$week_ago, $now])
		->orderBy('sci_invention_delta', 'DESC')
		->take(5)
		->get();

		$reactions_delta_increase = SystemCostIndices::where('sci_solar_region_name', $region_name)
		->whereBetween('updated_at', [$week_ago, $now])
		->orderBy('sci_reaction_delta', 'DESC')
		->take(5)
		->get();

		$manufacturing_delta_decrease = SystemCostIndices::where('sci_solar_region_name', $region_name)
		->whereBetween('updated_at', [$week_ago, $now])
		->orderBy('sci_manufacturing_delta', 'ASC')
		->take(5)
		->get();

		$research_te_delta_decrease = SystemCostIndices::where('sci_solar_region_name', $region_name)
		->whereBetween('updated_at', [$week_ago, $now])
		->orderBy('sci_researching_time_efficiency_delta', 'ASC')
		->take(5)
		->get();

		$research_me_delta_decrease = SystemCostIndices::where('sci_solar_region_name', $region_name)
		->whereBetween('updated_at', [$week_ago, $now])
		->orderBy('sci_researching_material_efficiency_delta', 'ASC')
		->take(5)
		->get();

		$copying_delta_decrease = SystemCostIndices::where('sci_solar_region_name', $region_name)
		->whereBetween('updated_at', [$week_ago, $now])
		->orderBy('sci_copying_delta', 'ASC')
		->take(5)
		->get();

		$invention_delta_decrease = SystemCostIndices::where('sci_solar_region_name', $region_name)
		->whereBetween('updated_at', [$week_ago, $now])
		->orderBy('sci_invention_delta', 'ASC')
		->take(5)
		->get();

		$reactions_delta_decrease = SystemCostIndices::where('sci_solar_region_name', $region_name)
		->whereBetween('updated_at', [$week_ago, $now])
		->orderBy('sci_reaction_delta', 'ASC')
		->take(5)
		->get();

		*/

		return view('region.view', compact('alliances', 'keepstars', 'sotiyos', 'azbels', 'fortizars', 'online', 'manufacturing', 'region_name', 'structures', 'researching_te', 'researching_me', 'copying', 'invention', 'reactions', 'capital_production', 'super_capital_production', 't2_rigged', 'jump_bridges', 'total_structures', 'oldest_structure', 'corporations', 'moons'
			/*'manufacturing_delta_increase', 'research_te_delta_increase', 'research_me_delta_increase', 'copying_delta_increase', 'invention_delta_increase', 'reactions_delta_increase', 'manufacturing_delta_decrease', 'research_te_delta_decrease', 'research_me_delta_decrease', 'copying_delta_decrease', 'invention_delta_decrease', 'reactions_delta_decrease'
			*/
		));
	}
}
