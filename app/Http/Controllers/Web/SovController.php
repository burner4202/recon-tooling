<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Vanguard\SovStructures;

use Carbon\Carbon;

use Input;

class SovController extends Controller
{
	public function index() {

		// Get System Index for Yesterday

		$search = Input::input('search');
		$input_per_page = Input::input('no_per_page');
		$input_structure_type = Input::input('structure_type');
		$input_bridge_in_system = Input::input('bridge_in_system');
		$input_supers_in_system = Input::input('supers_in_system');
		$input_keepstar_in_system = Input::input('keepstar_in_system');
		$input_vul_to = Input::input('vulnerable_to');
		$input_vul_from = Input::input('vulnerable_from');
		$input_vul_day = Input::input('vulnerable_day');

		$input_alliance = Input::input('alliance');
		$input_region = Input::input('region');

		$no_per_page = 	['100' => '100', '500' => '500'];
		$structure_type = 	['' => 'All', 'Infrastructure Hub' => 'Infrastructure Hub', 'Territorial Claim Unit' => 'Territorial Claim Unit'];
		$supers_in_system = ['' => 'All', 'Yes' => 'Yes', 'No' => 'No'];
		$bridge_in_system = ['' => 'All', 'Yes' => 'Yes', 'No' => 'No'];
		$keepstar_in_system = ['' => 'All', 'Yes' => 'Yes', 'No' => 'No'];

		$vulnerable_from = array();
		$vulnerable_to = array();
		$vulnerable_day = array();

		$vulnerable_from = ['' => 'All'];
		$vulnerable_to = ['' => 'All'];
		$vulnerable_day = ['' => 'All'];

		$vul_day_first = SovStructures::orderBy('vulnerable_start_time', 'ASC')->where('vulnerability_occupancy_level', '>', 0)->first();
		$vul_day_last = SovStructures::orderBy('vulnerable_start_time', 'DESC')->where('vulnerability_occupancy_level', '>', 0)->first();

		$vulnerable_day[$vul_day_first->vulnerable_start_time->format('l')] = $vul_day_first->vulnerable_start_time->format('l');
		$vulnerable_day[$vul_day_last->vulnerable_start_time->format('l')] = $vul_day_last->vulnerable_start_time->format('l');

		$iTimestamp = mktime(1,0,0,1,1,2011);
		for ($i = 0; $i < 24; $i++) {
			$vulnerable_from[date('H:i:s', $iTimestamp)] = date('H:i:s', $iTimestamp);
			$vulnerable_to[date('H:i:s', $iTimestamp)] = date('H:i:s', $iTimestamp);
			$iTimestamp += 3600;
		}

		$alliance = ['' => 'All'];

		$alliances = SovStructures::orderBy('alliance_name')->groupBy('alliance_name')->get();

		foreach($alliances as $each_alliance) { 
			if($each_alliance['alliance_name'] == "") {
				// DO Nothing & Don't fucking overright my blank array!
			} else {
				$alliance[$each_alliance['alliance_name']] = $each_alliance['alliance_name']; 
			}
		}

		$region = ['' => 'All'];

		$regions = SovStructures::orderBy('region_name')->groupBy('region_name')->get();

		foreach($regions as $each_region) { 
			if($each_region['region_name'] == "") {
				// DO Nothing & Don't fucking overright my blank array!
			} else {
				$region[$each_region['region_name']] = $each_region['region_name']; 
			}
		}

		if($input_per_page == "") { $per_page = 100; } else { $per_page = $input_per_page; }

		$query = SovStructures::query();

		if ($search) {
			$query->where(function ($q) use ($search) {
				$q->sortable();
				$q->where('solar_system_name', "like", "%{$search}%");
				$q->orWhere('constellation_name', 'like', "%{$search}%");
				$q->orWhere('region_name', 'like', "%{$search}%");
				$q->orWhere('alliance_name', 'like', "%{$search}%");
				$q->orWhere('alliance_ticker', 'like', "%{$search}%");
			});
		}

		if($input_structure_type == "Infrastructure Hub") {
			$query->where('structure_type_name', 'Infrastructure Hub');
		}

		if($input_structure_type == "Territorial Claim Unit") {
			$query->where('structure_type_name', 'Territorial Claim Unit');
		}

		if($input_supers_in_system == "Yes") {
			$query->where('supers_in_system', 1);
		}

		if($input_supers_in_system == "No") {
			$query->where('supers_in_system', 0);
		}


		if($input_bridge_in_system == "Yes") {
			$query->where('bridge_in_system', 1);
		}

		if($input_bridge_in_system == "No") {
			$query->where('bridge_in_system', 0);
		}

		if($input_keepstar_in_system == "Yes") {
			$query->where('keepstar_in_system', 1);
		}

		if($input_keepstar_in_system == "No") {
			$query->where('keepstar_in_system', 0);
		}

		if($input_region) {
			$query->where('region_name', $input_region);
		}

		if($input_alliance) {
			$query->where('alliance_name', $input_alliance);
		}

		/*

		if($input_vul_to) {
			$vul_to = Carbon::parse($input_vul_day . ' ' . $input_vul_to);
			$query->where('vulnerable_start_time', '<=',  $vul_to->toDateTimeString());
		}

		if($input_vul_from) {
			$vul_from = Carbon::parse($input_vul_day . ' ' . $input_vul_from);
			$query->where('vulnerable_start_time', '>=', $vul_from->toDateTimeString());

		}
		*/

		$sovereignty = $query
		->sortable()
		->orderBy('solar_system_name', 'ASC')
		->paginate($per_page);

		if ($search) {
			$sovereignty->appends(['search' => $search]);
		}

		return view('sovereignty.index', compact('sovereignty', 'no_per_page', 'structure_type', 'supers_in_system', 'bridge_in_system', 'alliance', 'region', 'vulnerable_from', 'vulnerable_to', 'keepstar_in_system', 'vulnerable_day'));

	}
}
