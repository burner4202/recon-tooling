<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Vanguard\PublicContracts;

use Input;

class PublicContractsController extends Controller
{

	public function index() {

		$search = Input::input('search');

		$type = 		[
			'' => 'All',
			'Titans' => 'Titans',
			'Supers' => 'Supers',
			'Carriers' => 'Carriers',
			'Dreads' => 'Dreads',
			'Faxes' => 'Faxes',

		];

		$npc = 		[
			'' => 'All',
			'Yes' => 'Yes',
		];

		$standings = 		[
			'' => 'All',
			'Friendly' => 'Friendly',
			'Neutral' => 'Neutral',
			'Hostile' => 'Hostile',
		];

		$regions = 		[
			'' => 'All',
			'Delve' => 'Delve',
			'Fountain' => 'Fountain',
			'Period Basis' => 'Period Basis',
			'Querious' => 'Querious',
		];

		$alliance = ['' => 'All'];

		$alliances = PublicContracts::orderBy('alliance_name')->groupBy('alliance_name')->get();

		foreach($alliances as $each_alliance) { 
			if($each_alliance['alliance_name'] == "") {
				// DO Nothing & Don't fucking overright my blank array!
			} else {
				$alliance[$each_alliance['alliance_name']] = $each_alliance['alliance_name']; 
			}
		}

		$input_type = Input::input('type');
		$input_standings = Input::input('standings');
		$input_regions = Input::input('regions');
		$input_npc = Input::input('npc');
		$input_alliance = Input::input('alliance');

		$query = PublicContracts::query();

		if ($input_alliance) {
			$query->where('alliance_name', $input_alliance);
		}

		if ($input_type == "Titans") {
			$query->where('is_titan', 1);
		}

		if ($input_type == "Supers") {
			$query->where('is_super', 1);
		}

		if ($input_type == "Carriers") {
			$query->where('is_carrier', 1);
		}

		if ($input_type == "Dreads") {
			$query->where('is_dread', 1);
		}

		if ($input_type == "Faxes") {
			$query->where('is_fax', 1);
		}

		if ($input_npc == "Yes") {
			$query->where('is_npc_delve', 1);
		}

		if ($input_standings == "Friendly") {
			$query->where('standing', '>', 0.00);
		}

		if ($input_standings == "Hostile") {
			$query->where('standing', '<', 0.00);
		}

		if ($input_standings == "Neutral") {
			$query->where('standing', '=', 0.00);
		}
		if ($input_regions == "Delve") {
			$query->where('region_name', "Delve");
		}

		if ($input_regions == "Fountain") {
			$query->where('region_name', "Fountain");
		}

		if ($input_regions == "Querious") {
			$query->where('region_name', "Querious");
		}

		if ($input_regions == "Period Basis") {
			$query->where('region_name', "Period Basis");
		}

		if ($search) {
			$query->where(function ($q) use ($search) {
				$q->where('type_name', "like", "%{$search}%");
				$q->orWhere('region_name', 'like', "%{$search}%");
				$q->orWhere('character_name', 'like', "%{$search}%");
				$q->orWhere('corporation_name', 'like', "%{$search}%");
				$q->orWhere('alliance_name', 'like', "%{$search}%");
			});
		}

		$contracts = $query
		->sortable()
		->orderBy('date_issued', 'DESC')
		->paginate(100);

		if ($search) {
			$contracts->appends(['search' => $search]);
		}

		$weeks = $query->selectRaw(
			'
			YEAR(created_at) year, 
			MONTHNAME(created_at) month, 
			WEEK(created_at) week, 
			COUNT( (CASE WHEN is_titan = 1 THEN is_titan END) ) AS is_titan,
			COUNT( (CASE WHEN is_carrier = 1 THEN is_carrier END) ) AS is_carrier,
			COUNT( (CASE WHEN is_fax = 1 THEN is_fax END) ) AS is_fax,
			COUNT( (CASE WHEN is_dread = 1 THEN is_dread END) ) AS is_dread,
			COUNT( (CASE WHEN is_super = 1 THEN is_super END) ) AS is_super,
			COUNT( (CASE WHEN is_npc_delve = 1 THEN is_npc_delve END) ) AS is_npc_delve,
			COUNT( (CASE WHEN standing = 0.00 THEN standing END) ) AS is_neutral_contract,
			COUNT( (CASE WHEN standing > 0.00 THEN standing END) ) AS is_friendly_contract,
			COUNT( (CASE WHEN standing < 0.00 THEN standing END) ) AS is_hostile_contract
			')
		->groupBy('year', 'month', 'week')
		->orderBy('created_at', 'desc')
		->get();

		$chart_weeks = $weeks->reverse();
		# Init Chart Array
		$chart = array();

		# Make Stacked Chart Array from Data for ChartJS.
		foreach($chart_weeks as $week) {

			$chart['Week (' . $week->week . ') ' . $week->month . "-" . $week->year] = [
				'is_titan' => $week->is_titan,
				'is_carrier' => $week->is_carrier,
				'is_fax' => $week->is_fax,
				'is_dread' => $week->is_dread,
				'is_super' => $week->is_super,
				'is_npc_delve' => $week->is_npc_delve,
				'is_neutral_contract' => $week->is_neutral_contract,
				'is_friendly_contract' => $week->is_friendly_contract,
				'is_hostile_contract' => $week->is_hostile_contract,
			];

		}

		return view('public_contracts.index', compact('contracts', 'type', 'standings', 'regions', 'npc', 'alliance', 'chart'));
	}

}
