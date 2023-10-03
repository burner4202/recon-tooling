<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Vanguard\CurrentlyClockedIn;
use Vanguard\WatchedSystems;
use Vanguard\WatchedSystemsDscan;
use Carbon\Carbon;

class CoordinationDashAPIController extends Controller
{
	public function active_fleets() {
		$active_fleets = CurrentlyClockedIn::where('active', 1)->get();
		$empty = array();

		if($active_fleets) {
			# Found active fleets!
			return response()->json($active_fleets, 200);
		} else {
			# No active fleets
			return response()->json($empty, 200);
		}
	}

	public function watched_systems() {

		$now = Carbon::now();
		$old = $now->subMinutes(15)->toDateTimeString();

		$watched_systems = WatchedSystems::orderBy('updated_at', 'DESC')
		->where('updated_at', '>', $old)
		->get();
		$empty = array();

		if($watched_systems) {
			# Found watched systems
			return response()->json($watched_systems, 200);
		} else {
			# No systems
			return response()->json($empty, 200);
		}
	}

	public function watched_systems_dscan() {

		$now = Carbon::now();
		$old = $now->subMinutes(15)->toDateTimeString();

		$watched_systems = WatchedSystemsDscan::orderBy('updated_at', 'DESC')
		->where('updated_at', '>', $old)
		->get();
		$empty = array();

		if($watched_systems) {
			# Found watched systems
			return response()->json($watched_systems, 200);
		} else {
			# No systems
			return response()->json($empty, 200);
		}
	}
}
