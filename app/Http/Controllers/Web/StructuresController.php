<?php

/*
 * Goonswarm Federation Recon Tools
 *
 * Developed by scopehone <scopeh@gmail.com>
 * In conjuction with Natalya Spaghet & Mindstar Technology
 *
 */

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Datetime;

use Auth;
use Log;
use DB;
use Input;
use Carbon\Carbon;
use Vanguard\User;
use Vanguard\SolarSystems;
use Vanguard\Regions;
use Vanguard\Constellations;
use Vanguard\TypeIDs;
use Vanguard\NPCKills;
use Vanguard\KnownStructures;
use Vanguard\Corporations;
use Vanguard\Alliances;
use Vanguard\UpwellModules;
use Vanguard\ActivityTracker;
use Vanguard\MarketPrices;
use Vanguard\UpwellRigs;
use Vanguard\NewMoons;
use Vanguard\AllianceStandings;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

// Excel Exports

use Maatwebsite\Excel\Facades\Excel;
use Vanguard\Exports\StructuresExport;

class StructuresController extends Controller
{
	// Main Index
	public function index() {

		$now = Carbon::now();

		$search = Input::input('search');

		$input_region = Input::input('region');
		$input_constellation = Input::input('constellation');
		$input_system = Input::input('system');

		$input_type = Input::input('type');
		$input_state = Input::input('state');
		$input_size = Input::input('size');
		$input_package = Input::input('package');
		$input_rarity = Input::input('rarity');
		$input_cored = Input::input('cored');
		$input_status = Input::input('status');
		$input_corporation = Input::input('corporation');
		$input_alliance = Input::input('alliance');

		$input_per_page = Input::input('no_per_page');
		$input_how_old = Input::input('how_old');

		$input_standings = Input::input('standings');

		$input_download_excel = Input::has('download_excel');

		// Check Boxes
		$check_t2rigged = Input::input('t2rigged');
		$check_moon_reactions = Input::input('moon_reactions');
		$check_reprocessing = Input::input('reprocessing');
		$check_moon_drilling = Input::input('moon_drilling');
		$check_hybrid = Input::input('hybrid');
		$check_invention = Input::input('invention');
		$check_researching = Input::input('researching');
		$check_hyasyoda = Input::input('hyasyoda');
		$check_market = Input::input('market');
		$check_cloning = Input::input('cloning');
		$check_titan_production = Input::input('titan_production');
		$check_cap_production = Input::input('cap_production');
		$check_dooms_day = Input::input('dooms_day');
		$check_point_defense = Input::input('point_defense');
		$check_guide_bombs = Input::input('guide_bombs');
		$check_anti_cap = Input::input('anti_cap');
		$check_anti_sub_cap = Input::input('anti_sub_cap');
		$check_on_hitlist = Input::input('on_hitlist');


		$systems = KnownStructures::groupBy('str_system')->orderBy('str_system', 'ASC')->get();
		$types = KnownStructures::groupBy('str_type')->orderBy('str_type', 'ASC')->get();
		$states = KnownStructures::groupBy('str_state')->orderBy('str_state', 'ASC')->get();
		$sizes = KnownStructures::groupBy('str_size')->orderBy('str_size', 'ASC')->get();
		$packages = KnownStructures::groupBy('str_package_delivered')->orderBy('str_package_delivered', 'ASC')->get();
		$statuses = KnownStructures::groupBy('str_status')->orderBy('str_status', 'ASC')->get();
		$corporations = KnownStructures::groupBy('str_owner_corporation_name')->orderBy('str_owner_corporation_name', 'ASC')->get();

		$system = 		['' => 'All'];
		$type = 		['' => 'All', 'All Faction Fortizars' => 'All Faction Fortizars'];
		$state = 		['' => 'All'];
		$size = 		['' => 'All'];
		$package = 		['' => 'All', 'No Package' => 'No Package'];
		$rarity = 		['' => 'All', 'R64' => 'R64', 'R32' => 'R32', 'R16' => 'R16', 'R8' => 'R8', 'R4' => 'R4'];
		$cored = 		['' => 'All', 'Yes' => 'Yes', 'No' => 'No'];
		$how_old = 		['' => 'All', '1 Month' => '1 Month', '2 Months' => '2 Months'];
		$corporation = 	['' => 'All'];
		$status = 		['' => 'All'];
		$no_per_page = 	['100' => '100', '500' => '500', '1000' => '1000'];
		$standings = 	['' => 'All', 'Blue' => 'Blue', 'Hostile' => 'Hostile', 'Neutral' => 'Neutral'];

		if($input_per_page == "") {
			$per_page = 100;
		} elseif($input_per_page > 1000) {
			$per_page = 100;
		} else {
			$per_page = $input_per_page;
		}

		foreach($systems as $each_system) {
			// We also have a system, so we don't need an if statement as below.
			$system[$each_system['str_system']] = $each_system['str_system'];
		}
		foreach($types as $each_type) {
			if($each_type['str_type'] == "") {
				// DO Nothing & Don't fucking overright my blank array!
			} else {
				$type[$each_type['str_type']] = $each_type['str_type'];
			}
		}
		foreach($states as $each_state) {
			if($each_state['str_state'] == "") {
				// DO Nothing
			} else {
				$state[$each_state['str_state']] = $each_state['str_state'];
			}
		}
		foreach($sizes as $each_size) {
			if($each_size['str_size'] == "") {
				// DO Nothing
			} else {
				$size[$each_size['str_size']] = $each_size['str_size'];
			}
		}

		foreach($packages as $each_package) {
			if($each_package['str_package_delivered'] == "") {
				// Do Nothing
			} else {
				$package[$each_package['str_package_delivered']] = $each_package['str_package_delivered'];
			}
		}

		foreach($statuses as $each_status) {
			if($each_status['str_status'] == "") {
				// DO Nothing
			} else {
				$status[$each_status['str_status']] = $each_status['str_status'];
			}
		}

		foreach($corporations as $each_corporation) {
			if($each_corporation['str_owner_corporation_name'] == "") {
				// DO fuck all as above.
			} else {
				$corporation[$each_corporation['str_owner_corporation_name']] = $each_corporation['str_owner_corporation_name'];
			}
		}

		$query = KnownStructures::query();

		if ($input_how_old) {
			if($input_how_old == '1 Month') {
				$query->whereDate('updated_at', '>=', $now->subMonth(1));
			} elseif($input_how_old == '2 Months') {
				$query->whereDate('updated_at', '>=', $now->subMonth(2));
			}
		}

		if ($input_system) {
			$query->where('str_system', $input_system);
		}

		if ($input_region) {
			$query->where('str_region_name', $input_region);
		}

		if ($input_constellation) {
			$query->where('str_constellation_name', $input_constellation);
		}

		if ($input_type) {
			if($input_type == 'All Faction Fortizars') {
				$query->where('str_type', "like", "%' Fortizar");
			} else {
				$query->where('str_type', $input_type);
			}
		}

		if ($input_state) {
			$query->where('str_state', $input_state);
		}

		if ($input_status) {
			$query->where('str_status', $input_status);
		}

		if ($input_size) {
			$query->where('str_size', $input_size);
		}

		if ($input_package) {
			if($input_package == 'No Package') {
				$query->where('str_package_delivered', "");
			} else {
				$query->where('str_package_delivered', $input_package);
			}
		}

		if ($input_rarity) {
			if($input_rarity == 'R64') {
				$query->where('str_moon_rarity', 64);
			} elseif($input_rarity == 'R32') {
				$query->where('str_moon_rarity', 32);
			} elseif($input_rarity == 'R16') {
				$query->where('str_moon_rarity', 16);
			} elseif($input_rarity == 'R8') {
				$query->where('str_moon_rarity', 8);
			} elseif($input_rarity == 'R4') {
				$query->where('str_moon_rarity', 4);
			}
		}

		if ($input_cored) {
			if($input_cored === 'Yes') {
				$query->where('str_cored', "Yes");
			} else {
				$query->where('str_cored', "No");
			}
		}

		if ($input_standings) {
			if($input_standings == 'Blue') {
				$query->where('str_standings', '>', 0);
			} elseif($input_standings == 'Hostile') {
				$query->where('str_standings', '<', 0);
			} else {
				$query->where('str_standings', 0);
			}
		}

		if ($input_corporation) {
			$query->where('str_owner_corporation_name', $input_corporation);
		}

		if ($input_alliance) {
			$query->where('str_owner_alliance_name', $input_alliance);
		}

		if ($check_t2rigged) {
			$query->where('str_t2_rigged', 1);
		}

		if ($check_moon_reactions) {
			$query->where('str_composite', 1);
		}

		if ($check_reprocessing) {
			$query->where('str_reprocessing', 1);
		}

		if ($check_moon_drilling) {
			$query->where('str_moon_drilling', 1);
		}

		if ($check_hybrid) {
			$query->where('str_hybrid', 1);
		}

		if ($check_invention) {
			$query->where('str_invention', 1);
		}

		if ($check_researching) {
			$query->where('str_research', 1);
		}

		if ($check_hyasyoda) {
			$query->where('str_hyasyoda', 1);
		}

		if ($check_market) {
			$query->where('str_market', 1);
		}

		if ($check_cloning) {
			$query->where('str_cloning', 1);
		}

		if ($check_titan_production) {
			$query->where('str_supercapital_shipyard', 1);
		}

		if ($check_cap_production) {
			$query->where('str_capital_shipyard', 1);
		}

		if ($check_dooms_day) {
			$query->where('str_dooms_day', 1);
		}

		if ($check_point_defense) {
			$query->where('str_point_defense', 1);
		}

		if ($check_guide_bombs) {
			$query->where('str_guide_bombs', 1);
		}

		if ($check_anti_cap) {
			$query->where('str_anti_cap', 1);
		}

		if ($check_anti_sub_cap) {
			$query->where('str_anti_subcap', 1);
		}

		if ($check_on_hitlist && Auth::user()->hasPermission('structure.hitlist')) {
			$query->where('str_hitlist', 1);
		}


		if ($search) {
			$query->where(function ($q) use ($search) {
				$q->sortable();
				$q->where('str_name', "like", "%{$search}%");
				$q->orWhere('str_type', 'like', "%{$search}%");
				$q->orWhere('str_state', 'like', "%{$search}%");
				$q->orWhere('str_size', 'like', "%{$search}%");
				$q->orWhere('str_status', 'like', "%{$search}%");
				$q->orWhere('str_system', 'like', "%{$search}%");
				$q->orWhere('str_region_name', 'like', "%{$search}%");
				$q->orWhere('str_constellation_name', 'like', "%{$search}%");
				$q->orWhere('str_owner_corporation_name', 'like', "%{$search}%");
				$q->orWhere('str_owner_alliance_name', 'like', "%{$search}%");
				$q->orWhere('str_owner_alliance_ticker', 'like', "%{$search}%");

			});
		}

		$structures = $query
		->sortable()
		->orderBy('updated_at', 'DESC')
		->where('str_destroyed', 0)
		->paginate($per_page);

		if ($search) {
			$structures->appends(['search' => $search]);
		}

		return view('structures.index', compact('structures', 'types', 'system', 'type', 'state', 'status', 'corporation', 'no_per_page', 'size', 'package', 'how_old', 'standings', 'cored', 'rarity'));

	}

	// Orphans
	public function orphans() {

		$search = Input::input('search');

		$input_region = Input::input('region');
		$input_system = Input::input('system');

		$input_type = Input::input('type');
		$input_state = Input::input('state');
		$input_status = Input::input('status');
		$input_corporation = Input::input('corporation');
		$input_alliance = Input::input('alliance');

		$systems = KnownStructures::groupBy('str_system')->orderBy('str_system', 'ASC')->get();
		$types = KnownStructures::groupBy('str_type')->orderBy('str_type', 'ASC')->get();
		$states = KnownStructures::groupBy('str_state')->orderBy('str_state', 'ASC')->get();
		$statuses = KnownStructures::groupBy('str_status')->orderBy('str_status', 'ASC')->get();
		$corporations = KnownStructures::groupBy('str_owner_corporation_name')->orderBy('str_owner_corporation_name', 'ASC')->get();

		$system = 		['' => 'All'];
		$type = 		['' => 'All'];
		$state = 		['' => 'All'];
		$corporation = 	['' => 'All'];
		$status = 		['' => 'All'];

		foreach($systems as $each_system) {
			// We also have a system, so we don't need an if statement as below.
			$system[$each_system['str_system']] = $each_system['str_system'];
		}
		foreach($types as $each_type) {
			if($each_type['str_type'] == "") {
				// DO Nothing & Don't fucking overright my blank array!
			} else {
				$type[$each_type['str_type']] = $each_type['str_type'];
			}
		}
		foreach($states as $each_state) {
			if($each_state['str_state'] == "") {
				// DO Nothing
			} else {
				$state[$each_state['str_state']] = $each_state['str_state'];
			}
		}
		foreach($statuses as $each_status) {
			if($each_status['str_status'] == "") {
				// DO Nothing
			} else {
				$status[$each_status['str_status']] = $each_status['str_status'];
			}
		}
		foreach($corporations as $each_corporation) {
			if($each_corporation['str_owner_corporation_name'] == "") {
				// DO fuck all as above.
			} else {
				$corporation[$each_corporation['str_owner_corporation_name']] = $each_corporation['str_owner_corporation_name'];
			}
		}

		$query = KnownStructures::query();

		if ($input_system) {
			$query->where('str_system', $input_system);
		}

		if ($input_region) {
			$query->where('str_region_name', $input_region);
		}

		if ($input_type) {
			$query->where('str_type', $input_type);
		}

		if ($input_state) {
			$query->where('str_state', $input_state);
		}

		if ($input_status) {
			$query->where('str_status', $input_status);
		}

		if ($input_corporation) {
			$query->where('str_owner_corporation_name', $input_corporation);
		}

		if ($input_alliance) {
			$query->where('str_owner_alliance_name', $input_alliance);
		}

		if ($search) {
			$query->where(function ($q) use ($search) {
				$q->sortable();
				$q->where('str_name', "like", "%{$search}%");
				$q->orWhere('str_type', 'like', "%{$search}%");
				$q->orWhere('str_state', 'like', "%{$search}%");
				$q->orWhere('str_status', 'like', "%{$search}%");
				$q->orWhere('str_system', 'like', "%{$search}%");
				$q->orWhere('str_region_name', 'like', "%{$search}%");
				$q->orWhere('str_owner_corporation_name', 'like', "%{$search}%");
				$q->orWhere('str_owner_alliance_name', 'like', "%{$search}%");

			});
		}


		$structures = $query
		->sortable()
		->orderBy('created_at', 'DESC')
		->where('str_owner_corporation_id', 0)
		->where('str_destroyed', 0)
		->paginate(500);

		if ($search) {
			$structures->appends(['search' => $search]);
		}

		return view('structures.orphans', compact('structures', 'types', 'system', 'type', 'state', 'status', 'corporation'));
	}

	// packageless
	public function packageless() {

		$search = Input::input('search');

		$input_region = Input::input('region');
		$input_system = Input::input('system');

		$input_type = Input::input('type');
		$input_state = Input::input('state');
		$input_status = Input::input('status');
		$input_corporation = Input::input('corporation');
		$input_alliance = Input::input('alliance');

		$systems = KnownStructures::groupBy('str_system')->orderBy('str_system', 'ASC')->get();
		$types = KnownStructures::groupBy('str_type')->orderBy('str_type', 'ASC')->get();
		$states = KnownStructures::groupBy('str_state')->orderBy('str_state', 'ASC')->get();
		$statuses = KnownStructures::groupBy('str_status')->orderBy('str_status', 'ASC')->get();
		$corporations = KnownStructures::groupBy('str_owner_corporation_name')->orderBy('str_owner_corporation_name', 'ASC')->get();

		$system = 		['' => 'All'];
		$type = 		['' => 'All'];
		$state = 		['' => 'All'];
		$corporation = 	['' => 'All'];
		$status = 		['' => 'All'];

		foreach($systems as $each_system) {
			// We also have a system, so we don't need an if statement as below.
			$system[$each_system['str_system']] = $each_system['str_system'];
		}
		foreach($types as $each_type) {
			if($each_type['str_type'] == "") {
				// DO Nothing & Don't fucking overright my blank array!
			} else {
				$type[$each_type['str_type']] = $each_type['str_type'];
			}
		}
		foreach($states as $each_state) {
			if($each_state['str_state'] == "") {
				// DO Nothing
			} else {
				$state[$each_state['str_state']] = $each_state['str_state'];
			}
		}
		foreach($statuses as $each_status) {
			if($each_status['str_status'] == "") {
				// DO Nothing
			} else {
				$status[$each_status['str_status']] = $each_status['str_status'];
			}
		}
		foreach($corporations as $each_corporation) {
			if($each_corporation['str_owner_corporation_name'] == "") {
				// DO fuck all as above.
			} else {
				$corporation[$each_corporation['str_owner_corporation_name']] = $each_corporation['str_owner_corporation_name'];
			}
		}

		$query = KnownStructures::query()->where('str_vertified_package', "=", "");

		if ($input_system) {
			$query->where('str_system', $input_system);
		}

		if ($input_region) {
			$query->where('str_region_name', $input_region);
		}

		if ($input_type) {
			$query->where('str_type', $input_type);
		}

		if ($input_state) {
			$query->where('str_state', $input_state);
		}

		if ($input_status) {
			$query->where('str_status', $input_status);
		}

		if ($input_corporation) {
			$query->where('str_owner_corporation_name', $input_corporation);
		}

		if ($input_alliance) {
			$query->where('str_owner_alliance_name', $input_alliance);
		}

		if ($search) {
			$query->where(function ($q) use ($search) {
				$q->sortable();
				$q->where('str_name', "like", "%{$search}%");
				$q->orWhere('str_type', 'like', "%{$search}%");
				$q->orWhere('str_state', 'like', "%{$search}%");
				$q->orWhere('str_status', 'like', "%{$search}%");
				$q->orWhere('str_system', 'like', "%{$search}%");
				$q->orWhere('str_region_name', 'like', "%{$search}%");
				$q->orWhere('str_owner_corporation_name', 'like', "%{$search}%");
				$q->orWhere('str_owner_alliance_name', 'like', "%{$search}%");

			});
		}


		$structures = $query
		->sortable()
		->orderBy('created_at', 'DESC')
		->paginate(500);

		if ($search) {
			$structures->appends(['search' => $search]);
		}

		return view('structures.packageless', compact('structures', 'types', 'system', 'type', 'state', 'status', 'corporation'));
	}

		// Destroyed
	public function package_review() {

		$search = Input::input('search');

		$input_region = Input::input('region');
		$input_system = Input::input('system');

		$input_type = Input::input('type');
		$input_state = Input::input('state');
		$input_status = Input::input('status');
		$input_corporation = Input::input('corporation');
		$input_alliance = Input::input('alliance');

		$systems = KnownStructures::groupBy('str_system')->orderBy('str_system', 'ASC')->get();
		$types = KnownStructures::groupBy('str_type')->orderBy('str_type', 'ASC')->get();
		$states = KnownStructures::groupBy('str_state')->orderBy('str_state', 'ASC')->get();
		$statuses = KnownStructures::groupBy('str_status')->orderBy('str_status', 'ASC')->get();
		$corporations = KnownStructures::groupBy('str_owner_corporation_name')->orderBy('str_owner_corporation_name', 'ASC')->get();

		$system = 		['' => 'All'];
		$type = 		['' => 'All'];
		$state = 		['' => 'All'];
		$corporation = 	['' => 'All'];
		$status = 		['' => 'All'];

		foreach($systems as $each_system) {
			// We also have a system, so we don't need an if statement as below.
			$system[$each_system['str_system']] = $each_system['str_system'];
		}
		foreach($types as $each_type) {
			if($each_type['str_type'] == "") {
				// DO Nothing & Don't fucking overright my blank array!
			} else {
				$type[$each_type['str_type']] = $each_type['str_type'];
			}
		}
		foreach($states as $each_state) {
			if($each_state['str_state'] == "") {
				// DO Nothing
			} else {
				$state[$each_state['str_state']] = $each_state['str_state'];
			}
		}
		foreach($statuses as $each_status) {
			if($each_status['str_status'] == "") {
				// DO Nothing
			} else {
				$status[$each_status['str_status']] = $each_status['str_status'];
			}
		}
		foreach($corporations as $each_corporation) {
			if($each_corporation['str_owner_corporation_name'] == "") {
				// DO fuck all as above.
			} else {
				$corporation[$each_corporation['str_owner_corporation_name']] = $each_corporation['str_owner_corporation_name'];
			}
		}

		$query = KnownStructures::query();

		if ($input_system) {
			$query->where('str_system', $input_system);
		}

		if ($input_region) {
			$query->where('str_region_name', $input_region);
		}

		if ($input_type) {
			$query->where('str_type', $input_type);
		}

		if ($input_state) {
			$query->where('str_state', $input_state);
		}

		if ($input_status) {
			$query->where('str_status', $input_status);
		}

		if ($input_corporation) {
			$query->where('str_owner_corporation_name', $input_corporation);
		}

		if ($input_alliance) {
			$query->where('str_owner_alliance_name', $input_alliance);
		}

		if ($search) {
			$query->where(function ($q) use ($search) {
				$q->sortable();
				$q->where('str_name', "like", "%{$search}%");
				$q->orWhere('str_type', 'like', "%{$search}%");
				$q->orWhere('str_state', 'like', "%{$search}%");
				$q->orWhere('str_status', 'like', "%{$search}%");
				$q->orWhere('str_system', 'like', "%{$search}%");
				$q->orWhere('str_region_name', 'like', "%{$search}%");
				$q->orWhere('str_owner_corporation_name', 'like', "%{$search}%");
				$q->orWhere('str_owner_alliance_name', 'like', "%{$search}%");

			});
		}


		$structures = $query
		->sortable()
		->orderBy('updated_at', 'DESC')
		->where('str_destroyed', 2)
		->paginate(15);

		if ($search) {
			$structures->appends(['search' => $search]);
		}

		return view('structures.packages', compact('structures', 'types', 'system', 'type', 'state', 'status', 'corporation'));
	}


	// Destroyed
	public function destroyed() {

		$search = Input::input('search');

		$input_region = Input::input('region');
		$input_system = Input::input('system');

		$input_type = Input::input('type');
		$input_state = Input::input('state');
		$input_status = Input::input('status');
		$input_corporation = Input::input('corporation');
		$input_alliance = Input::input('alliance');

		$input_package = Input::input('package');

		$systems = KnownStructures::groupBy('str_system')->orderBy('str_system', 'ASC')->get();
		$types = KnownStructures::groupBy('str_type')->orderBy('str_type', 'ASC')->get();
		$states = KnownStructures::groupBy('str_state')->orderBy('str_state', 'ASC')->get();
		$statuses = KnownStructures::groupBy('str_status')->orderBy('str_status', 'ASC')->get();
		$corporations = KnownStructures::groupBy('str_owner_corporation_name')->orderBy('str_owner_corporation_name', 'ASC')->get();
		$packages = KnownStructures::groupBy('str_package_delivered')->orderBy('str_package_delivered', 'ASC')->get();


		$system = 		['' => 'All'];
		$type = 		['' => 'All'];
		$state = 		['' => 'All'];
		$corporation = 	['' => 'All'];
		$status = 		['' => 'All'];
		$package = 		['' => 'All', 'No Package' => 'No Package'];

		foreach($systems as $each_system) {
			// We also have a system, so we don't need an if statement as below.
			$system[$each_system['str_system']] = $each_system['str_system'];
		}
		foreach($types as $each_type) {
			if($each_type['str_type'] == "") {
				// DO Nothing & Don't fucking overright my blank array!
			} else {
				$type[$each_type['str_type']] = $each_type['str_type'];
			}
		}
		foreach($states as $each_state) {
			if($each_state['str_state'] == "") {
				// DO Nothing
			} else {
				$state[$each_state['str_state']] = $each_state['str_state'];
			}
		}
		foreach($statuses as $each_status) {
			if($each_status['str_status'] == "") {
				// DO Nothing
			} else {
				$status[$each_status['str_status']] = $each_status['str_status'];
			}
		}
		foreach($corporations as $each_corporation) {
			if($each_corporation['str_owner_corporation_name'] == "") {
				// DO fuck all as above.
			} else {
				$corporation[$each_corporation['str_owner_corporation_name']] = $each_corporation['str_owner_corporation_name'];
			}
		}

		foreach($packages as $each_package) {
			if($each_package['str_package_delivered'] == "") {
				// Do Nothing
			} else {
				$package[$each_package['str_package_delivered']] = $each_package['str_package_delivered'];
			}
		}


		$query = KnownStructures::query();

		if ($input_system) {
			$query->where('str_system', $input_system);
		}

		if ($input_region) {
			$query->where('str_region_name', $input_region);
		}

		if ($input_type) {
			$query->where('str_type', $input_type);
		}

		if ($input_state) {
			$query->where('str_state', $input_state);
		}

		if ($input_status) {
			$query->where('str_status', $input_status);
		}

		if ($input_corporation) {
			$query->where('str_owner_corporation_name', $input_corporation);
		}

		if ($input_alliance) {
			$query->where('str_owner_alliance_name', $input_alliance);
		}

		if ($input_package) {
			if($input_package == 'No Package') {
				$query->where('str_package_delivered', "");
			} else {
				$query->where('str_package_delivered', $input_package);
			}
		}


		if ($search) {
			$query->where(function ($q) use ($search) {
				$q->sortable();
				$q->where('str_name', "like", "%{$search}%");
				$q->orWhere('str_type', 'like', "%{$search}%");
				$q->orWhere('str_state', 'like', "%{$search}%");
				$q->orWhere('str_status', 'like', "%{$search}%");
				$q->orWhere('str_system', 'like', "%{$search}%");
				$q->orWhere('str_region_name', 'like', "%{$search}%");
				$q->orWhere('str_owner_corporation_name', 'like', "%{$search}%");
				$q->orWhere('str_owner_alliance_name', 'like', "%{$search}%");

			});
		}


		$structures = $query
		->sortable()
		->orderBy('updated_at', 'DESC')
		->where('str_destroyed', 1)
		->paginate(500);

		if ($search) {
			$structures->appends(['search' => $search]);
		}

		return view('structures.destroyed', compact('structures', 'types', 'system', 'type', 'state', 'status', 'corporation', 'package'));
	}

	// Moon Mining Module
	public function moon_drill() {

		$search = Input::input('search');

		$input_region = Input::input('region');
		$input_system = Input::input('system');

		$input_type = Input::input('type');
		$input_state = Input::input('state');
		$input_status = Input::input('status');
		$input_corporation = Input::input('corporation');
		$input_alliance = Input::input('alliance');

		$systems = KnownStructures::groupBy('str_system')->orderBy('str_system', 'ASC')->get();
		$types = KnownStructures::groupBy('str_type')->orderBy('str_type', 'ASC')->get();
		$states = KnownStructures::groupBy('str_state')->orderBy('str_state', 'ASC')->get();
		$statuses = KnownStructures::groupBy('str_status')->orderBy('str_status', 'ASC')->get();
		$corporations = KnownStructures::groupBy('str_owner_corporation_name')->orderBy('str_owner_corporation_name', 'ASC')->get();

		$system = 		['' => 'All'];
		$type = 		['' => 'All'];
		$state = 		['' => 'All'];
		$corporation = 	['' => 'All'];
		$status = 		['' => 'All'];

		foreach($systems as $each_system) {
			// We also have a system, so we don't need an if statement as below.
			$system[$each_system['str_system']] = $each_system['str_system'];
		}
		foreach($types as $each_type) {
			if($each_type['str_type'] == "") {
				// DO Nothing & Don't fucking overright my blank array!
			} else {
				$type[$each_type['str_type']] = $each_type['str_type'];
			}
		}
		foreach($states as $each_state) {
			if($each_state['str_state'] == "") {
				// DO Nothing
			} else {
				$state[$each_state['str_state']] = $each_state['str_state'];
			}
		}
		foreach($statuses as $each_status) {
			if($each_status['str_status'] == "") {
				// DO Nothing
			} else {
				$status[$each_status['str_status']] = $each_status['str_status'];
			}
		}
		foreach($corporations as $each_corporation) {
			if($each_corporation['str_owner_corporation_name'] == "") {
				// DO fuck all as above.
			} else {
				$corporation[$each_corporation['str_owner_corporation_name']] = $each_corporation['str_owner_corporation_name'];
			}
		}

		$query = KnownStructures::query();

		if ($input_system) {
			$query->where('str_system', $input_system);
		}

		if ($input_region) {
			$query->where('str_region_name', $input_region);
		}

		if ($input_type) {
			$query->where('str_type', $input_type);
		}

		if ($input_state) {
			$query->where('str_state', $input_state);
		}

		if ($input_status) {
			$query->where('str_status', $input_status);
		}

		if ($input_corporation) {
			$query->where('str_owner_corporation_name', $input_corporation);
		}

		if ($input_alliance) {
			$query->where('str_owner_alliance_name', $input_alliance);
		}

		if ($search) {
			$query->where(function ($q) use ($search) {
				$q->sortable();
				$q->where('str_name', "like", "%{$search}%");
				$q->orWhere('str_type', 'like', "%{$search}%");
				$q->orWhere('str_state', 'like', "%{$search}%");
				$q->orWhere('str_status', 'like', "%{$search}%");
				$q->orWhere('str_system', 'like', "%{$search}%");
				$q->orWhere('str_region_name', 'like', "%{$search}%");
				$q->orWhere('str_owner_corporation_name', 'like', "%{$search}%");
				$q->orWhere('str_owner_alliance_name', 'like', "%{$search}%");

			});
		}

		// Need to come back to this and fix it!
		$structures = $query
		->sortable()
		->orderBy('created_at', 'DESC')
		->where('str_destroyed', 1)
		->whereRaw('json_contains(str_fitting, \'{"name": "Standup Moon Drill I"}\')')
		->paginate(15);

		dd($structures);

		if ($search) {
			$structures->appends(['search' => $search]);
		}

		return view('structures.moon_drill', compact('structures', 'types', 'system', 'type', 'state', 'status', 'corporation'));
	}

	// Vulnerable
	public function vulnerable() {

		$search = Input::input('search');

		$input_region = Input::input('region');
		$input_system = Input::input('system');
		$input_vulnerable = Input::input('vulnerable');

		$input_type = Input::input('type');
		$input_state = Input::input('state');
		$input_status = Input::input('status');
		$input_corporation = Input::input('corporation');
		$input_alliance = Input::input('alliance');

		$systems = KnownStructures::groupBy('str_system')->orderBy('str_system', 'ASC')->get();
		$types = KnownStructures::groupBy('str_type')->orderBy('str_type', 'ASC')->get();
		$states = KnownStructures::groupBy('str_state')->orderBy('str_state', 'ASC')->get();
		$statuses = KnownStructures::groupBy('str_status')->orderBy('str_status', 'ASC')->get();
		$corporations = KnownStructures::groupBy('str_owner_corporation_name')->orderBy('str_owner_corporation_name', 'ASC')->get();

		$system = 		['' => 'All'];
		$type = 		['' => 'All'];
		$state = 		['' => 'All'];
		$corporation = 	['' => 'All'];
		$status = 		['' => 'All'];
		$vulnerable = 		[
			'Monday' => 'Monday',
			'Tuesday' => 'Tuesday',
			'Wednesday' => 'Wednesday',
			'Thursday' => 'Thursday',
			'Friday' => 'Friday',
			'Saturday' => 'Saturday',
			'Sunday' => 'Sunday'
		];

		foreach($systems as $each_system) {
			// We also have a system, so we don't need an if statement as below.
			$system[$each_system['str_system']] = $each_system['str_system'];
		}
		foreach($types as $each_type) {
			if($each_type['str_type'] == "") {
				// DO Nothing & Don't fucking overright my blank array!
			} else {
				$type[$each_type['str_type']] = $each_type['str_type'];
			}
		}
		foreach($states as $each_state) {
			if($each_state['str_state'] == "") {
				// DO Nothing
			} else {
				$state[$each_state['str_state']] = $each_state['str_state'];
			}
		}
		foreach($statuses as $each_status) {
			if($each_status['str_status'] == "") {
				// DO Nothing
			} else {
				$status[$each_status['str_status']] = $each_status['str_status'];
			}
		}
		foreach($corporations as $each_corporation) {
			if($each_corporation['str_owner_corporation_name'] == "") {
				// DO fuck all as above.
			} else {
				$corporation[$each_corporation['str_owner_corporation_name']] = $each_corporation['str_owner_corporation_name'];
			}
		}

		$query = KnownStructures::query();

		if ($input_system) {
			$query->where('str_system', $input_system);
		}

		if ($input_region) {
			$query->where('str_region_name', $input_region);
		}

		if ($input_type) {
			$query->where('str_type', $input_type);
		}

		if ($input_state) {
			$query->where('str_state', $input_state);
		}

		if ($input_status) {
			$query->where('str_status', $input_status);
		}

		if ($input_corporation) {
			$query->where('str_owner_corporation_name', $input_corporation);
		}

		if ($input_alliance) {
			$query->where('str_owner_alliance_name', $input_alliance);
		}

		if ($search) {
			$query->where(function ($q) use ($search) {
				$q->sortable();
				$q->where('str_name', "like", "%{$search}%");
				$q->orWhere('str_type', 'like', "%{$search}%");
				$q->orWhere('str_state', 'like', "%{$search}%");
				$q->orWhere('str_status', 'like', "%{$search}%");
				$q->orWhere('str_system', 'like', "%{$search}%");
				$q->orWhere('str_region_name', 'like', "%{$search}%");
				$q->orWhere('str_owner_corporation_name', 'like', "%{$search}%");
				$q->orWhere('str_owner_alliance_name', 'like', "%{$search}%");

			});
		}

		// Day of Week

		$day = Carbon::now()->format('l');
		$now = Carbon::now();

		$structures = $query
		->sortable()
		->where('str_destroyed', 0)
		->where('str_vul_day', $input_vulnerable)
		//->where('str_vul_hour', '<', $now)
		->orderBy('str_vul_hour', 'ASC')
		->paginate(15);

		if ($search) {
			$structures->appends(['search' => $search]);
		}

		return view('structures.vulnerable', compact('structures', 'types', 'system', 'type', 'state', 'status', 'corporation', 'now', 'vulnerable'));
	}

	// Vulnerable
	public function abandoned() {

		$search = Input::input('search');

		$input_region = Input::input('region');
		$input_system = Input::input('system');
		$input_vulnerable = Input::input('vulnerable');

		$input_type = Input::input('type');
		$input_state = Input::input('state');
		$input_status = Input::input('status');
		$input_corporation = Input::input('corporation');
		$input_alliance = Input::input('alliance');

		$systems = KnownStructures::groupBy('str_system')->orderBy('str_system', 'ASC')->get();
		$types = KnownStructures::groupBy('str_type')->orderBy('str_type', 'ASC')->get();
		$states = KnownStructures::groupBy('str_state')->orderBy('str_state', 'ASC')->get();
		$statuses = KnownStructures::groupBy('str_status')->orderBy('str_status', 'ASC')->get();
		$corporations = KnownStructures::groupBy('str_owner_corporation_name')->orderBy('str_owner_corporation_name', 'ASC')->get();

		$system = 		['' => 'All'];
		$type = 		['' => 'All'];
		$state = 		['' => 'All'];
		$corporation = 	['' => 'All'];
		$status = 		['' => 'All'];

		foreach($systems as $each_system) {
			// We also have a system, so we don't need an if statement as below.
			$system[$each_system['str_system']] = $each_system['str_system'];
		}
		foreach($types as $each_type) {
			if($each_type['str_type'] == "") {
				// DO Nothing & Don't fucking overright my blank array!
			} else {
				$type[$each_type['str_type']] = $each_type['str_type'];
			}
		}
		foreach($states as $each_state) {
			if($each_state['str_state'] == "") {
				// DO Nothing
			} else {
				$state[$each_state['str_state']] = $each_state['str_state'];
			}
		}
		foreach($statuses as $each_status) {
			if($each_status['str_status'] == "") {
				// DO Nothing
			} else {
				$status[$each_status['str_status']] = $each_status['str_status'];
			}
		}
		foreach($corporations as $each_corporation) {
			if($each_corporation['str_owner_corporation_name'] == "") {
				// DO fuck all as above.
			} else {
				$corporation[$each_corporation['str_owner_corporation_name']] = $each_corporation['str_owner_corporation_name'];
			}
		}

		$query = KnownStructures::query();

		if ($input_system) {
			$query->where('str_system', $input_system);
		}

		if ($input_region) {
			$query->where('str_region_name', $input_region);
		}

		if ($input_type) {
			$query->where('str_type', $input_type);
		}

		if ($input_state) {
			$query->where('str_state', $input_state);
		}

		if ($input_status) {
			$query->where('str_status', $input_status);
		}

		if ($input_corporation) {
			$query->where('str_owner_corporation_name', $input_corporation);
		}

		if ($input_alliance) {
			$query->where('str_owner_alliance_name', $input_alliance);
		}

		if ($search) {
			$query->where(function ($q) use ($search) {
				$q->sortable();
				$q->where('str_name', "like", "%{$search}%");
				$q->orWhere('str_type', 'like', "%{$search}%");
				$q->orWhere('str_state', 'like', "%{$search}%");
				$q->orWhere('str_status', 'like', "%{$search}%");
				$q->orWhere('str_system', 'like', "%{$search}%");
				$q->orWhere('str_region_name', 'like', "%{$search}%");
				$q->orWhere('str_owner_corporation_name', 'like', "%{$search}%");
				$q->orWhere('str_owner_alliance_name', 'like', "%{$search}%");

			});
		}

		$structures = $query
		->sortable()
		->where('str_destroyed', 0)
		->where('str_state', "Abandoned")
		->orderBy('str_abandoned_time', 'ASC')
		->paginate(200);

		if ($search) {
			$structures->appends(['search' => $search]);
		}

		return view('structures.abandoned', compact('structures', 'types', 'system', 'type', 'state', 'status', 'corporation'));
	}




	public function autocomplete(Request $request)
	{

		$data = KnownStructures::
		where("str_name","LIKE","%{$request->input('query')}%")
		->get();

		return response()->json($data);
	}

	public function addToHitlist($structure_id)
	{

		$user_id = Auth::id();

		$structure = KnownStructures::where('str_structure_id_md5', $structure_id)
		->first();

		if($structure->str_hitlist == 1) {

			return redirect()->back()
			->withErrors($structure->str_name . " is already on the Hitlist.");
		}

		$structure->str_hitlist = 1;
		$structure->timestamps = false;
		$structure->save();

		$action = "Structure added to the Hitlist.";
		$this->addActivityLogToStructure($user_id, $structure, $action);

		return redirect()->back()
		->withSuccess($structure->str_name . " has been added to the Hitlist");

	}

	public function removeFromHitlist($structure_id)
	{

		$user_id = Auth::id();

		$structure = KnownStructures::where('str_structure_id_md5', $structure_id)
		->first();

		if($structure->str_hitlist == 0) {

			return redirect()->back()
			->withErrors($structure->str_name . " is not on the Hitlist.");
		}

		$structure->str_hitlist = 0;
		$structure->timestamps = false;
		$structure->save();

		$action = "Structure removed from the Hitlist.";
		$this->addActivityLogToStructure($user_id, $structure, $action);

		return redirect()->back()
		->withSuccess($structure->str_name . " has been removed to the Hitlist");

	}

	public function clearHitlist()
	{

		KnownStructures::where('str_hitlist', 1)->update(['str_hitlist' => 0]);

		return redirect()->back()
		->withSuccess("Hitlist Cleared");

	}


	public function stateHighPower($structure_id)
	{

		$user_id = Auth::id();

		$structure = KnownStructures::where('str_structure_id_md5', $structure_id)
		->first();

		if($structure->str_state === "High Power") {

			return redirect()->back()
			->withErrors($structure->str_name . " is already set as High Power");
		}

		$structure->str_state = "High Power";
		$structure->str_abandoned_time = "";
		$structure->save();

		$action = "Structure set to High Power";
		$this->addActivityLogToStructure($user_id, $structure, $action);

		return redirect()->back()
		->withSuccess($structure->str_name . " set as High Power");

	}

	public function stateLowPower($structure_id)
	{

		$user_id = Auth::id();

		$structure = KnownStructures::where('str_structure_id_md5', $structure_id)
		->first();

		if($structure->str_state === "Low Power") {

			return redirect()->back()
			->withErrors($structure->str_name . " is already set as Low Power");
		}

		$structure->str_state = "Low Power";
		$structure->str_abandoned_time = "";
		$structure->save();

		$action = "Structure set to Low Power";
		$this->addActivityLogToStructure($user_id, $structure, $action);

		return redirect()->back()
		->withSuccess($structure->str_name . " set as Low Power");

	}

	public function stateAbandoned($structure_id)
	{

		$user_id = Auth::id();

		$structure = KnownStructures::where('str_structure_id_md5', $structure_id)
		->first();

		if($structure->str_state === "Abandoned") {

			return redirect()->back()
			->withErrors($structure->str_name . " is already set as Abandoned");
		}

		$now = Carbon::now();

		$structure->str_state = "Abandoned";
		$structure->str_abandoned_time = $now;

		$structure->save();

		$action = "Structure set to Abandoned";
		$this->addActivityLogToStructure($user_id, $structure, $action);

		return redirect()->back()
		->withSuccess($structure->str_name . " set as Abandoned");

	}

	public function stateAnchoring($structure_id)
	{

		$user_id = Auth::id();

		$structure = KnownStructures::where('str_structure_id_md5', $structure_id)
		->first();

		if($structure->str_state === "Anchoring") {

			return redirect()->back()
			->withErrors($structure->str_name . " is already set as Anchoring");
		}

		$structure->str_state = "Anchoring";
		$structure->save();

		$action = "Structure set to Anchoring";
		$this->addActivityLogToStructure($user_id, $structure, $action);

		return redirect()->back()
		->withSuccess($structure->str_name . " set as Anchoring");

	}

	public function statusUnanchoring($structure_id)
	{

		$user_id = Auth::id();

		$structure = KnownStructures::where('str_structure_id_md5', $structure_id)
		->first();

		if($structure->str_status === "Unanchoring") {

			return redirect()->back()
			->withErrors($structure->str_name . " is already set as Unanchoring");
		}

		$structure->str_status = "Unanchoring";
		$structure->save();

		$action = "Structure set to Unanchoring";
		$this->addActivityLogToStructure($user_id, $structure, $action);

		return redirect()->back()
		->withSuccess($structure->str_name . " set as Unanchoring");

	}

	public function statusReinforced($structure_id)
	{
		$user_id = Auth::id();

		$structure = KnownStructures::where('str_structure_id_md5', $structure_id)
		->first();

		if($structure->str_status === "Armor") {

			$structure->str_status = "Hull";
			$structure->save();

			$action = "Structure is reinforced to Hull";
			$this->addActivityLogToStructure($user_id, $structure, $action);

			return redirect()->back()
			->withSuccess($structure->str_name . " has marked structure to Hull Reinforced");
		}

		$structure->str_status = "Armor";
		$structure->save();

		$action = "Structure is reinforced to Armor";
		$this->addActivityLogToStructure($user_id, $structure, $action);

		return redirect()->back()
		->withSuccess($structure->str_name . " has marked structure to Armor Reinforced");

	}

	public function statusReinforcedClear($structure_id)
	{
		$user_id = Auth::id();

		$structure = KnownStructures::where('str_structure_id_md5', $structure_id)
		->first();

		if($structure->str_status === "") {

			return redirect()->back()
			->withErrors($structure->str_name . " Status is already clear");
		}

		$structure->str_status = "";
		$structure->save();

		$action = "Structure Status Cleared.";
		$this->addActivityLogToStructure($user_id, $structure, $action);

		return redirect()->back()
		->withSuccess($structure->str_name . " set status cleared.");

	}

	public function packageDelivered($structure_id)
	{
		$user_id = Auth::id();

		$structure = KnownStructures::where('str_structure_id_md5', $structure_id)
		->first();

		if($structure->str_package_delivered === "Package Delivered") {

			$structure->str_package_delivered = "Package Removed";
			$structure->save();

			$action = "Package Removed";

			$this->addActivityLogToStructure($user_id, $structure, $action);

			return redirect()->back()
			->withSuccess($structure->str_name . " Package Removed");
		}

		$structure->str_package_delivered = "Package Delivered";
		$structure->save();

		$action = "Package Delivered";
		$this->addActivityLogToStructure($user_id, $structure, $action);

		return redirect()->back()
		->withSuccess($structure->str_name . " Package Delivered");

	}

	public function fitting($structure_id)
	{
		$user_id = Auth::id();


		$structure = KnownStructures::where('str_structure_id_md5', $structure_id)
		->first();

		if($structure->str_has_no_fitting === "Fitted") {

			$structure->str_has_no_fitting = "No Fitting";
			$structure->str_market						= 0;
			$structure->str_capital_shipyard			= 0;
			$structure->str_hyasyoda					= 0;
			$structure->str_invention					= 0;
			$structure->str_manufacturing				= 0;
			$structure->str_research					= 0;
			$structure->str_supercapital_shipyard		= 0;
			$structure->str_biochemical					= 0;
			$structure->str_hybrid						= 0;
			$structure->str_moon_drilling				= 0;
			$structure->str_reprocessing				= 0;
			$structure->str_point_defense				= 0;
			$structure->str_dooms_day					= 0;
			$structure->str_guide_bombs					= 0;
			$structure->str_anti_cap					= 0;
			$structure->str_anti_subcap					= 0;
			$structure->str_t2_rigged					= 0;
			$structure->str_fitting 					= null;
			$structure->str_value  						= 0;
			$structure->save();

			$action = "Structure has No Fitting";

			$this->addActivityLogToStructure($user_id, $structure, $action);

			return redirect()->back()
			->withSuccess($structure->str_name . " has been marked as having No Fitting");
		}

		$structure->str_has_no_fitting = "Fitted";
		$structure->save();

		$action = "Structure has a Fit";
		$this->addActivityLogToStructure($user_id, $structure, $action);

		return redirect()->back()
		->withSuccess($structure->str_name . " set as Fitted");

	}

	public function cored($structure_id)
	{
		$user_id = Auth::id();


		$structure = KnownStructures::where('str_structure_id_md5', $structure_id)
		->first();

		if($structure->str_cored === "Yes") {

			$structure->str_cored = "No";

			$structure->save();

			$action = "Structure has No Core";

			$this->addActivityLogToStructure($user_id, $structure, $action);

			return redirect()->back()
			->withSuccess($structure->str_name . " has been marked as having No Core");
		}

		$structure->str_cored = "Yes";
		$structure->save();

		$action = "Structure has a Core";
		$this->addActivityLogToStructure($user_id, $structure, $action);

		return redirect()->back()
		->withSuccess($structure->str_name . " has be marked as Cored");

	}


	public function destroy($structure_id)
	{

		$user_id = Auth::id();

		$structure = KnownStructures::where('str_structure_id_md5', $structure_id)
		->first();

		$destroy_date = Carbon::now()->format('d-m-Y-H-i-s');

		$md5 = $structure->str_structure_id_md5;
		$new_hash = $md5 . "-dead-" . $destroy_date;

		$structure->str_structure_id_md5 = $new_hash;
		$structure->str_destroyed = 1;
		$structure->save();
		$action = "Structure Destroyed";

		$this->addActivityLogToStructure($user_id, $structure, $action);

		# Migrate Activity Log from Structure to new Hash.

		$this->migrateActivityLogToDestroyedStructure($md5, $new_hash);

		# Redirect back to system.
		return redirect()->route('solar.system', $structure->str_system_id)
		->withSuccess($structure->str_name ."...  Its dead Jim");
	}


	public function migrateActivityLogToDestroyedStructure($structure_hash, $new_structure_hash) {

		# Get all previous activity.
		$activity_logs = ActivityTracker::where('at_structure_hash', $structure_hash)->get();

		# Migrate it to new hash.

		if($activity_logs) {

			foreach($activity_logs as $activity) {

				# Update Records

				ActivityTracker::where(
					[
						'at_structure_hash' => $activity->at_structure_hash,
					]
				)->update(
					[
						'at_structure_hash' => $new_structure_hash
					]

				);

			}
		}
	}



	public function view($hashId) {


		$structure = KnownStructures::where('str_structure_id_md5', $hashId)->first();

		# Duplicate Structures

		$duplicate_structures = KnownStructures::where('str_structure_id', $structure->str_structure_id)
		->where('str_structure_id', '>', 1)
		->get();

		$moon = array();
		$moons = NewMoons::where('moon_system_id', $structure->str_system_id)->select('moon_name')->get();

		foreach($moons as $each_moon) {
			$moon[$each_moon['moon_name']] = $each_moon['moon_name'];
		}

		if(Auth::user()->hasPermission('structure.hitlist')) {

			$actions = ActivityTracker::where('at_structure_hash', $hashId)
			->orderBy('created_at','DESC')
			->paginate(15);

		} else {

			$actions = ActivityTracker::where('at_structure_hash', $hashId)
			->orderBy('created_at','DESC')
			->where('at_action', '!=', 'Structure added to the Hitlist.')
			->paginate(15);
		}

		$vul_day = [
			'Monday' => 'Monday',
			'Tuesday' => 'Tuesday',
			'Wednesday' => 'Wednesday',
			'Thursday' => 'Thursday',
			'Friday' => 'Friday',
			'Saturday' => 'Saturday',
			'Sunday' => 'Sunday',
		];


		$vul_hour = array();

		$iTimestamp = mktime(1,0,0,1,1,2011);
		for ($i = 0; $i < 24; $i++) {
			$vul_hour[date('H:i:s', $iTimestamp)] = date('H:i:s', $iTimestamp);
			$iTimestamp += 3600;
		}

		# Get Moon Data.

		if($structure->str_moon) {
			$moon_data = NewMoons::where('moon_name', $structure->str_moon)->first();
		} else {
			$moon_data = "";
		}


		return view('structures.view', compact('structure', 'actions', 'vul_day', 'vul_hour', 'moon', 'moon_data', 'duplicate_structures'));


	}

	public function storeStructureDscan($structure_id)
	{

		$structures = array();
		$store = array();

		$request = Input::all();

		if($request['title'] == null) {
			return redirect()->back()
			->withErrors('Fill the box you dick.');
		}

		if(strpos($request['title'], '<url=showinfo:') == false) {
			return redirect()->back()
			->withErrors('Invalid Dscan Parse');
		}


		// Check to make sure names match.

		$exploded = explode("<url=showinfo:", $request['title']);
		$type_id_structure_id = explode("//", $exploded[1]);
		$type_id = $type_id_structure_id[0];
		$parsed_structure_id = substr($type_id_structure_id[1], 0, 13);
		$structure_name = rtrim(substr($type_id_structure_id[1], 14), "</url>");



		// Corporations are in Brackets, this checks to se eif brackets are present for validation
		$corporation =  preg_match_all('/\(([^\)]+)\)/', $structure_name, $matches);

		$owner = $matches[1];

		if(strlen($parsed_structure_id)  != 13) {
			return redirect()->back()
			->withErrors('Invalid Dscan Parse');
		} else {

			if($this->validStructure($type_id)) {

				$owner_id = $this->getCorporationID(end($owner));
				    // Can't find corporation in database, add it.
				if (!$owner_id) {
					   // Get ID
					$owner_id = $this->searchEVE(end($owner));
					   // Add to database
					$this->getCorporation($owner_id);
				}

				// If structure ID exists, we want to remake the hash and update it
				// Can happen if wrong link is put in wrong citadel or.. citadel is renamed.
				// We grab the structure data from the database



				// Now we want to check if the structure ID already exists in the database
				// We check to see if we have more than one entry.
				/* TOOK THIS OUT AND HAVE TO REVIEW.
				$existing_structure = KnownStructures::where('str_structure_id', $parsed_structure_id)->first();

				if(isset($existing_structure)) {

					// We should send the user to the existing structure id. instead of updating meta data.
					// They can always do it in the new
					// But we should update the structure name too incase of name change.


					$new_name = strstr($structure_name, '(', TRUE);
					$trimmed_new_name = rtrim($new_name, " ");

					// Get new structure information
					$new_structure = KnownStructures::where('str_structure_id_md5', $structure_id)->first();

					// Mark it as dead
					$new_structure->str_destroyed = 2;
					$new_structure->save();

					// Update The Existing Structure with new name and hash
					$existing_structure->str_name = $trimmed_new_name;
					$existing_structure->str_structure_id_md5 = $structure_id;
					$existing_structure->save();

					return redirect()->route('structures.view', $structure_id)
					->withErrors('We found an existing structure with this ID, it has been renamed... please populate this one instead!');

				}
				*/

				$corporation = Corporations::where('corporation_corporation_id', $owner_id)->first();
				$alliance = Alliances::where('alliance_alliance_id', $corporation->corporation_alliance_id)->first();

				$addStructure = KnownStructures::updateOrCreate([
					'str_structure_id_md5' 				 => $structure_id,
				],[
					'str_structure_id'     				 => $parsed_structure_id,
					'str_owner_corporation_name'     	 => end($owner),
					'str_owner_corporation_id'     	 	 => $owner_id,
				]);

				if($corporation->corporation_alliance_id > 1) {

					$update = KnownStructures::where('str_structure_id_md5', $structure_id)->first();
					$update->str_owner_alliance_id = $alliance->alliance_alliance_id;
					$update->str_owner_alliance_name = $alliance->alliance_name;
					$update->str_owner_alliance_ticker = $alliance->alliance_ticker;
					$update->save();

				} else {

					$update = KnownStructures::where('str_structure_id_md5', $structure_id)->first();
					$update->str_owner_alliance_id = 0;
					$update->str_owner_alliance_name = null;
					$update->str_owner_alliance_ticker = null;
					$update->save();
				}

				# 19/2/20
				# Check Structure Owners Standings and Add to the Structure

				## Alliance or Corporation
				## If the owner isn't in an alliance, search the standings for the corporation.

				if($corporation->corporation_alliance_id > 1) {

					$standing_check = $alliance->alliance_alliance_id;

				} else {
					# owner_id is always the corporation owner.
					$standing_check = $owner_id;
				}

				# Search Standings , Corporation or Alliance
				$standings = AllianceStandings::where('as_contact_id', $standing_check)->first();

				# Found Standings - Add to Structure
				if($standings) {

					$add_standings = KnownStructures::where('str_structure_id_md5', $structure_id)->first();
					$add_standings->str_standings = $standings->as_standing;
					$add_standings->save();
				} else {

					# Standings were not found, this is a neutral structure
					$add_standings = KnownStructures::where('str_structure_id_md5', $structure_id)->first();
					$add_standings->str_standings = 0;
					$add_standings->save();
				}


				// Add Action to Activity Log
				$user_id = Auth::id();
				$action = "Structure Belongs to " . end($owner);
				$this->addActivityLogToStructureMeta($user_id, $structure_id, $parsed_structure_id, $owner_id, end($owner), $action);


			}

		}

		return redirect()->back()
		->withSuccess('Piece of Cake...');
	}

	public function storeStructureFittingDscan($structure_id)
	{

		$structures = array();
		$store = array();

		$request = Input::all();

		if($request['title'] == null) {
			return redirect()->back()
			->withErrors('Fill the box you dick.');
		}

		try {

			$dscan = $request['title'];

		// Need to add some validation
		//if(!strstr($dscan, "High Power Slots")) {
		//	return redirect()->back()
		//	->withErrors('Invalid Scan.');
		//}

		// Remove all the garbage and the end of file.
			$remove_eof = str_replace("\r", "", $dscan);

		// Explode into an array please, thank you.
			$fitting = $dscan = explode("\n", $remove_eof);


		// Find the Garage & Remove it

		 // Initilize what to delete
			$delete_val = array(
				"High Power Slots",
				"Medium Power Slots",
				"Low Power Slots",
				"Rig Slots",
				"Service Slots",
				"Charges",
			);

			$sanitisedFitting = array_diff($fitting, $delete_val);


		// Now we grab the information of the modules from our pre seeded database, wow.
		// We have to cycle it, because its an array so we use a foreach. Meh

		/*
		 * array:9 [â–¼
  		 * 0 => "Standup Multirole Missile Launcher I"
         * 1 => "Standup Stasis Webifier I"
         * 2 => "Standup Target Painter I"
         * 3 => "Standup Ballistic Control System I"
         * 4 => "Standup M-Set Moon Drilling Efficiency I"
         * 5 => "Standup M-Set Moon Drilling Stability I"
         * 6 => "Standup Cloning Center I"
         * 7 => "Standup Manufacturing Plant I"
         * 8 => "Standup Moon Drill I"
         * ]
		 */

		// Init Array for Building JSON information.

		$fittingArray = array();
		$fittingValue = 0;

		// We set up module mapping before adding it to the struture database

		$cloning = 0;
		$market = 0;
		$capital_shipyard = 0;
		$hyasyoda = 0;
		$invention = 0;
		$manufacturing = 0;
		$research = 0;
		$supercapital_shipyard = 0;
		$biochemical = 0;
		$composite = 0;
		$hybrid = 0;
		$moon_drilling = 0;
		$reprocessing = 0;
		$point_defense = 0;
		$dooms_day = 0;
		$guide_bombs = 0;
		$anti_cap = 0;
		$anti_subcap = 0;
		$t2_rigged = 0;

		foreach($sanitisedFitting as $module) {

			// Map Module



			if($module == "Standup Cloning Center I") {	$cloning = 1; }
			if($module == "Standup Market Hub I") {	$market = 1; }
			if($module == "Standup Capital Shipyard I") { $capital_shipyard = 1; }
			if($module == "Standup Hyasyoda Research Lab") { $hyasyoda = 1; }
			if($module == "Standup Invention Lab I") { $invention = 1; }
			if($module == "Standup Manufacturing Plant I") { $manufacturing = 1; }
			if($module == "Standup Research Lab I") { $research = 1; }
			if($module == "Standup Supercapital Shipyard I") { $supercapital_shipyard = 1; }
			if($module == "Standup Biochemical Reactor I") { $biochemical = 1; }
			if($module == "Standup Composite Reactor I") { $composite = 1; }
			if($module == "Standup Hybrid Reactor I") {	$hybrid = 1; }
			if($module == "Standup Moon Drill I") {	$moon_drilling = 1; }
			if($module == "Standup Reprocessing Facility I") {	$reprocessing = 1; }
			if($module == "Standup Point Defense Battery I" || $module == "Standup Point Defense Battery II") {	$point_defense = 1; }
			if($module == "Standup Arcing Vorton Projector I") { $dooms_day = 1; }
			if($module == "Standup Guided Bomb Launcher I" || $module == "Standup Guided Bomb Launcher II") { $guide_bombs = 1; }
			if($module == "Standup Anticapital Missile Launcher I" || $module == "Standup Anticapital Missile Launcher II") { $anti_cap = 1; }
			if($module == "Standup Multirole Missile Launcher I" || $module == "Standup Multirole Missile Launcher II") { $anti_subcap = 1; }
			if($module == "Standup Market Hub I") {	$market = 1; }

			if($module == "Standup Conduit Generator I") {
				return redirect()->back()
				->withErrors('This module is prefitted to this structure & has no value.');
			}

			if($module == "Standup Cynosural Field Generator I") {
				return redirect()->back()
				->withErrors('This module is prefitted to this structure & has no value.');
			}

			if($module == "Standup Cynosural System Jammer I") {
				return redirect()->back()
				->withErrors('This module is prefitted to this structure & has no value.');
			}


			// use Regex Preg Match to find a t2 rig.

			if (preg_match('/(?=.*-Set)(?=.*II)/', $module)) {
				$t2_rigged = 1;
			}

			// Does this module exist in our own database?

			$exists = UpwellModules::where('upm_name', $module)->first();

			if ($exists) {

				// If the module does exist we get the price of the module from our own database that we update every day
				// Get the price of the module, Average in 2 last months.

				$manufactured_rig = UpwellRigs::where('name', $module)->first();

				// Check if we have manufacture cost for rig.

				if($manufactured_rig) {
					// Define Value that we have calculated from salvage.
					$priceOfModule = $manufactured_rig->value;

				} else {
					// Get Value from Market.

					$to = Carbon::today()->format('Y-m-d');
					$from = Carbon::today()->subMonth(1)->format('Y-m-d');

					// We take the average value of the average values of the past 2 months for calculations.
					$priceOfModule = MarketPrices::where('type_id', $exists->upm_type_id)
					->whereBetween('date', [$from, $to])
					->max('average');
				}

				$fittingValue += $priceOfModule;

				// Build the JSON Array
				$fittingArray[] = [
					'type_id' => $exists->upm_type_id,
					'name'   => $exists->upm_name,
					'price' => $priceOfModule,
				];

				// END IF

			} else {

				// The Module didn't exists so we add it to our database.

				// Get the Module ID

				$module_id = $this->searchEVETypeID($module);

				// Get The Type ID Information from CCP
				$response = $this->getTypeID($module_id);

				// Drop it In our Database

				$update_type = UpwellModules::updateOrCreate([
					'upm_type_id'      				=> $module_id,
				],[
					'upm_name'  					=> $response->name,
				]);

				$priceOfModule = $this->getMarketPrice($module_id);
				$fittingValue += $priceOfModule;

				$fittingArray[] = [
					'type_id' => $module_id,
					'name'   => $response->name,
					'price' => $priceOfModule,
				];
			// END IF
			}
			// END OF FOREACH


		}

		// JSON it up and add it to the structure meta data.
		$addFittingToStructure = KnownStructures::updateOrCreate([
			'str_structure_id_md5'      	=> $structure_id,
		],[
			'str_fitting'  					=> json_encode($fittingArray),
			'str_value'						=> $fittingValue,
			'str_has_no_fitting' 			=> "Fitted",
			'str_market'					=> $market,
			'str_capital_shipyard'			=> $capital_shipyard,
			'str_hyasyoda'					=> $hyasyoda,
			'str_invention'					=> $invention,
			'str_manufacturing'				=> $manufacturing,
			'str_research'					=> $research,
			'str_supercapital_shipyard'		=> $supercapital_shipyard,
			'str_biochemical'				=> $biochemical,
			'str_hybrid'					=> $hybrid,
			'str_moon_drilling'				=> $moon_drilling,
			'str_reprocessing'				=> $reprocessing,
			'str_point_defense'				=> $point_defense,
			'str_dooms_day'					=> $dooms_day,
			'str_guide_bombs'				=> $guide_bombs,
			'str_anti_cap'					=> $anti_cap,
			'str_anti_subcap'				=> $anti_subcap,
			'str_t2_rigged'					=> $t2_rigged,
			'str_cloning'					=> $cloning,
			'str_composite'					=> $composite,
		]);

		// Add Action to Activity Log
		$user_id = Auth::id();
		$action = "Stored Structure Fitting";
		$this->addActivityLogToStructureFitting($user_id, $structure_id, $action);


		return redirect()->back()
		->withSuccess('Added Fitting to the Structure');

	} catch (Exception $e) {

		return redirect()->back()
		->withErrors('Invalid Fitting');

	}

}


public function containsWord($str, $word)
{
	return !!preg_match('#\\b' . preg_quote($word, '#') . '\\b#i', $str);
}


public function getCorporationID($corporation_name) {

	$corporation = Corporations::where('corporation_name', $corporation_name)
	->where('corporation_ceo_id', '>', 1)
	->first();

	if(!isset($corporation)) {
		return false;
	} else {
		return $corporation->corporation_corporation_id;
	}
}

public function validStructure($structure_id) {
	$structures = [
    		//Fortizars
		'35832',
		'35833',
		'35834',
    		// Faction Fortizars
		'47512',
		'47513',
		'47514',
		'47515',
		'47516',
		'40340',
   			// Engineering
		'35825',
		'35826',
		'35827',
		'35840',
			// StructuresCyno Etc
		'35841',
		'35841',
		'37534',
			// Moon
		'35835',
		'35836',
	];

	if (in_array($structure_id, $structures)) {
		return true;
	} else {
		return false;
	}
}

public function setVulnerabilityWindow($structure_id) {

	$user_id = Auth::id();

	$day = Input::input('vul_day');
	$hour = Input::input('vul_hour');

	$structure = KnownStructures::where('str_structure_id_md5', $structure_id)
	->first();
	$structure->str_vul_day = $day;
	$structure->str_vul_hour = $hour;
	$structure->save();

	$action = "Vulnerability Window Set to " . $day . ' @ ' . $hour;
	$this->addActivityLogToStructure($user_id, $structure, $action);

	return redirect()->back()
	->withSuccess($structure->str_name . " Vulnerability Day/Time Updated");

}

public function setAnchoredMoon($structure_id) {

	$user_id = Auth::id();

	$moon = Input::input('moon');

	$moon_data = NewMoons::where('moon_name', $moon)->first();

	if($moon_data->moon_r_rating > 1) {
		$moon_rarity = $moon_data->moon_r_rating;
	} else {
		$moon_rarity = "";
	}

	$structure = KnownStructures::where('str_structure_id_md5', $structure_id)
	->first();
	$structure->str_moon = $moon;
	$structure->str_moon_rarity = $moon_rarity;
	$structure->save();

	$action = "Anchored on " . $moon;
	$this->addActivityLogToStructure($user_id, $structure, $action);

	return redirect()->back()
	->withSuccess($structure->str_name . " anchored on " . $moon);

}

public function getCorporation($corporation_id)
{
	$configuration = Configuration::getInstance();

	$client_id = config('eve.client_id');
	$secret_key = config('eve.secret_key');

	try {

		$esi = new Eseye();

		$response = $esi->invoke('get', '/corporations/{corporation_id}/', [
			'corporation_id' => $corporation_id,
		]);

		if(!isset($response->alliance_id)) {
			$corp = Corporations::updateOrCreate([
				'corporation_corporation_id'      => $corporation_id,
			],[
				'corporation_ceo_id'            => $response->ceo_id,
				'corporation_creator_id'        => $response->creator_id,
				'corporation_date_founded'      => $response->date_founded,
				'corporation_member_count'      => $response->member_count,
				'corporation_name'              => $response->name,
				'corporation_tax_rate'          => $response->tax_rate,
				'corporation_ticker'            => $response->ticker,
			]);

				//$this->updateCharacter($response->creator_id);
				//$this->updateCharacter($response->ceo_id);


		} else  {

			$corp = Corporations::updateOrCreate([
				'corporation_corporation_id'      => $corporation_id,
			],[
				'corporation_alliance_id'       => $response->alliance_id,
				'corporation_ceo_id'            => $response->ceo_id,
				'corporation_creator_id'        => $response->creator_id,
				'corporation_date_founded'      => $response->date_founded,
				'corporation_member_count'      => $response->member_count,
				'corporation_name'              => $response->name,
				'corporation_tax_rate'          => $response->tax_rate,
				'corporation_ticker'            => $response->ticker,
			]);

				//$this->updateCharacter($response->creator_id);
				//$this->updateCharacter($response->ceo_id);
		}


	}  catch (EsiScopeAccessDeniedException $e) {

		return redirect()->back()
		->withErrors('SSO Token is invalid');


	} catch (RequestFailedException $e) {

		return redirect()->back()
		->withErrors('Got ESI Error');

	} catch (Exception $e) {

		return redirect()->back()
		->withErrors('ESI is fucked');
	}

}

public function searchEVE($search)
{

	try {
		$ammended = str_replace(" ", "%20", $search);
		$response = json_decode(file_get_contents('https://esi.evetech.net/latest/search/?categories=corporation&datasource=tranquility&language=en-us&search=' . $ammended . '&strict=true'));
		return $response->corporation['0'];
	} catch (Exception $e) {

		return redirect()->back()
		->withErrors('Invalid Fitting');

	}
}

public function searchEVETypeID($search)
{
	try {
		$ammended = str_replace(" ", "%20", $search);
		$response = json_decode(file_get_contents('https://esi.evetech.net/latest/search/?categories=inventory_type&datasource=tranquility&language=en-us&search=' . $ammended . '&strict=true'));

		return $response->inventory_type['0'];
	} catch (Exception $e) {

		return redirect()->back()
		->withErrors('Invalid Fitting');

	}
}

public function getMarketPrice($type_id)
{
	try {
		$marketSearch = collect(json_decode(file_get_contents('https://esi.evetech.net/v1/markets/10000002/history/?type_id=' . $type_id), true));
		$value = $marketSearch->max('average');

		return $value;
	} catch (Exception $e) {

		return redirect()->back()
		->withErrors('Invalid Fitting');

	}
}




    /**
     * Execute the console command.
     *
     * @return mixed
     */
	public function getTypeID($type_id) { // End Point: https://esi.evetech.net/ui/#/Universe/get_universe_types_type_id

		$configuration = Configuration::getInstance();

		$client_id = config('eve.client_id');
		$secret_key = config('eve.secret_key');

		$esi = new Eseye();

		try {

			$response = $esi->invoke('get', '/universe/types/{type_id}/', [
				'type_id' => $type_id,
			]);


		}  catch (EsiScopeAccessDeniedException $e) {

			return redirect()->back()
			->withErrors('SSO Token is invalid');

		} catch (RequestFailedException $e) {

			return redirect()->back()
			->withErrors('Got ESI Error');

		} catch (Exception $e) {

			return redirect()->back()
			->withErrors('ESI is fucked');
		}


		return $response;
	}

	public function addActivityLogToStructure($user_id, $structure, $user_action) {

		$user = User::where('id', $user_id)
		->first();

		$action = new ActivityTracker;
		$action->at_user_id = $user->id;
		$action->at_username = $user->username;
		$action->at_structure_id = $structure->str_structure_id;
		$action->at_structure_hash = $structure->str_structure_id_md5;
		$action->at_structure_name = $structure->str_name;
		$action->at_system_id = $structure->str_system_id;
		$action->at_system_name = $structure->str_system;
		$action->at_corporation_id = $structure->str_owner_corporation_id;
		$action->at_corporation_name = $structure->str_owner_corporation_name;
		$action->at_action = $user_action;
		$action->save();
	}

	public function addActivityLogToStructureMeta($user_id, $structure_id_md5, $structure_id, $corporation_id, $corporation_name, $user_action) {

		$user = User::where('id', $user_id)
		->first();

		$action = new ActivityTracker;
		$action->at_user_id = $user->id;
		$action->at_username = $user->username;
		$action->at_structure_id = $structure_id;
		$action->at_structure_hash = $structure_id_md5;
		$action->at_corporation_id = $corporation_id;
		$action->at_corporation_name = $corporation_name;
		$action->at_action = $user_action;
		$action->save();
	}

	public function addActivityLogToStructureFitting($user_id, $structure_id_md5, $user_action) {

		$user = User::where('id', $user_id)
		->first();

		$action = new ActivityTracker;
		$action->at_user_id = $user->id;
		$action->at_username = $user->username;
		$action->at_structure_hash = $structure_id_md5;
		$action->at_action = $user_action;
		$action->save();
	}

	public function exportToExcel()
	{

		$user = Auth::user();

		$date = Carbon::now();

		return Excel::download(new StructuresExport, $date . "-" . $user->username . ' Structure Export.xlsx');
	}

	public function exportHitlist()
	{

		$owner = Auth::user();


		# Run Query to get the goodies.
		$hitlist = KnownStructures::where('str_hitlist', 1)
		->where('str_destroyed', 0)
		->orderBy('str_name', 'asc')
		->select(
			'str_name',
			'str_type',
			'str_size',
			'str_state',
			'str_status',
			'str_vul_hour',
			'str_system',
			'str_constellation_name',
			'str_region_name',
			'str_owner_corporation_name',
			'str_owner_alliance_name',
			'str_owner_alliance_ticker',
			'str_value',
			'str_anti_cap',
			'str_anti_subcap',
			'str_point_defense',
			'str_guide_bombs',
			'str_market',
			'str_reprocessing',
			'str_manufacturing',
			'str_cloning',
			'str_moon_drilling',
			'str_capital_shipyard',
			'str_supercapital_shipyard',
			'str_t2_rigged',
			'str_moon',
			'updated_at'
		)
		->get();

		$filename = '../storage/hitlist/Hitlist_' . $owner->username . '_' . date("Y-m-d H:i") . '.csv';
		$fields = array('Structure Name', 'Type', 'Size', 'State', 'Status', 'Vulnerability Hour', 'System Name', 'Constellation',  'Region', 'Corporation Name', 'Alliance Name', 'Ticker', 'Fitting Value', 'Anti Capital Fit', 'Anti Subcapital Fit', 'Point Defence', 'Guided Bombs', 'Market Hub', 'Reprocessing', 'Manufacturing', 'Cloning', 'Moon Drilling', 'Capital Production', 'Super Capital Production', 'T2 Rigged', 'Anchored Moon', 'Last Updated');
		$export_data = $hitlist->toArray();

        // file creation
		$file = fopen($filename,"w");

        // Add Headers
		fputcsv($file, $fields);


		foreach ($export_data as $line){
			fputcsv($file, $line);
		}

		fclose($file);

         // download
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=".$filename);
		header("Content-Type: application/csv; ");

		readfile($filename);

       	 // deleting file
		//unlink($filename);
		exit();


	}

	public function exportHitlistDestroyed()
	{

		$owner = Auth::user();


		# Run Query to get the goodies.
		$hitlist = KnownStructures::where('str_hitlist', 1)
		->where('str_destroyed', 1)
		->orderBy('str_name', 'asc')
		->select(
			'str_name',
			'str_type',
			'str_size',
			'str_state',
			'str_status',
			'str_vul_hour',
			'str_system',
			'str_constellation_name',
			'str_region_name',
			'str_owner_corporation_name',
			'str_owner_alliance_name',
			'str_owner_alliance_ticker',
			'str_value',
			'str_anti_cap',
			'str_anti_subcap',
			'str_point_defense',
			'str_guide_bombs',
			'str_market',
			'str_reprocessing',
			'str_manufacturing',
			'str_cloning',
			'str_moon_drilling',
			'str_capital_shipyard',
			'str_supercapital_shipyard',
			'str_t2_rigged',
			'str_moon',
			'updated_at'
		)
		->get();

		$filename = '../storage/hitlist/Hitlist_Destroyed_' . $owner->username . '_' . date("Y-m-d H:i") . '.csv';
		$fields = array('Structure Name', 'Type', 'Size', 'State', 'Status', 'Vulnerability Hour', 'System Name', 'Constellation',  'Region', 'Corporation Name', 'Alliance Name', 'Ticker', 'Fitting Value', 'Anti Capital Fit', 'Anti Subcapital Fit', 'Point Defence', 'Guided Bombs', 'Market Hub', 'Reprocessing', 'Manufacturing', 'Cloning', 'Moon Drilling', 'Capital Production', 'Super Capital Production', 'T2 Rigged', 'Anchored Moon', 'Last Updated');
		$export_data = $hitlist->toArray();

        // file creation
		$file = fopen($filename,"w");

        // Add Headers
		fputcsv($file, $fields);


		foreach ($export_data as $line){
			fputcsv($file, $line);
		}

		fclose($file);

         // download
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=".$filename);
		header("Content-Type: application/csv; ");

		readfile($filename);

       	 // deleting file
		//unlink($filename);
		exit();


	}


}
