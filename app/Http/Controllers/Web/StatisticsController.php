<?php

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
use Vanguard\TaskManager;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class StatisticsController extends Controller
{
	public function index() {

		// Total Structures

		$input_date_from = Input::input('date-from');

		$input_date_to = Input::input('date-to');

		$input_region = Input::input('region');
		$input_constellation = Input::input('constellation');
		$input_system = Input::input('system');

		$input_type = Input::input('type');
		$input_state = Input::input('state');
		$input_size = Input::input('size');
		$input_status = Input::input('status');
		$input_corporation = Input::input('corporation');
		$input_alliance = Input::input('alliance');

		$types = KnownStructures::groupBy('str_type')->orderBy('str_type', 'ASC')->get();
		$states = KnownStructures::groupBy('str_state')->orderBy('str_state', 'ASC')->get();
		$sizes = KnownStructures::groupBy('str_size')->orderBy('str_size', 'ASC')->get();
		$statuses = KnownStructures::groupBy('str_status')->orderBy('str_status', 'ASC')->get();

		$system = 		['' => 'All'];
		$type = 		['' => 'All'];
		$state = 		['' => 'All'];
		$size = 		['' => 'All'];
		$corporation = 	['' => 'All'];
		$status = 		['' => 'All'];

		$structures = KnownStructures::get();
		$activity = ActivityTracker::get();
		$tasks = TaskManager::get();

		$online_structures = [];
		$dead_structures = [];



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
		foreach($statuses as $each_status) { 
			if($each_status['str_status'] == "") {
				// DO Nothing
			} else {
				$status[$each_status['str_status']] = $each_status['str_status']; 
			}
		}
		$query = KnownStructures::query();

		if ($input_date_from) {
			$query->whereBetween('created_at', [Carbon::parse($input_date_from), Carbon::parse($input_date_to)]);
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
			$query->where('str_type', $input_type);
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

		if ($input_corporation) {
			$query->where('str_owner_corporation_name', $input_corporation);
		}

		if ($input_alliance) {
			$query->where('str_owner_alliance_name', $input_alliance);
		}


		$structures = $query
		->sortable()
		->orderBy('updated_at', 'DESC')
		->get();

		if ($input_date_from) {
			dd($structures);
		}


		$online = DB::table('known_structures')
		->select(DB::raw('count(str_type) as number'), DB::raw('str_type as str_type'))
		->where('str_destroyed', 0)
		->groupBy('str_type')
		->orderBy('str_type', 'ASC')
		->get();
		/*
		$dead = DB::table('known_structures')
		->select(DB::raw('count(str_type) as number'), DB::raw('str_type as str_type'))
		->where('str_destroyed', 1)
		->groupBy('str_type')
		->orderBy('str_type', 'ASC')
		->get();

		*/

		foreach($online as $structure_count) {
			$online_structures[$structure_count->str_type] = $structure_count->number;
		}
		/*

		foreach($dead as $structure_count) {
			$dead_structures[$structure_count->str_type] = $structure_count->number;
		}
	*/


		return view('statistics.index', compact('structures', 'activity', 'tasks', 'online_structures', 'types', 'system', 'type', 'state', 'status'));
	}
}
