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

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class AutoCompleteController extends Controller
{

	public function universe(Request $request)
	{

		$systems = SolarSystems::
		where("ss_system_name","LIKE","%{$request->input('system')}%")
		->where("ss_constellation_name","LIKE","%{$request->input('system')}%")
		->where("ss_region_name","LIKE","%{$request->input('system')}%")
		->select('ss_system_name')
		->groupBy('ss_system_name')
		->get();

		$universe = array();
		foreach ($systems as $system) {

			$universe[] = $system->ss_system_name;
		}

		$constellations = SolarSystems::
		where("ss_constellation_name","LIKE","%{$request->input('query')}%")
		->groupBy('ss_constellation_name')
		->select('ss_constellation_name')
		->get();

		foreach ($constellations as $constellation) {

			$universe[] = $constellation->constellation;
		}

		$regions = SolarSystems::
		where("ss_region_name","LIKE","%{$request->input('query')}%")
		->groupBy('ss_region_name')
		->select('ss_region_name')
		->get();

		foreach ($regions as $region) {

			$universe[] = $region->ss_region_name;
		}

		return response()->json($universe);
	}

	public function systems(Request $request)
	{

		$data = SolarSystems::
		where("ss_system_name","LIKE","%{$request->input('system')}%")
		->select('ss_system_name')
		->groupBy('ss_system_name')
		->get();

		$systems = array();
		foreach ($data as $system) {

			$systems[] = $system->ss_system_name;
		}

		return response()->json($systems);
	}

	public function constellations(Request $request)
	{

		$data = SolarSystems::
		where("ss_constellation_name","LIKE","%{$request->input('query')}%")
		->groupBy('ss_constellation_name')
		->select('ss_constellation_name')
		->get();

		$constellations = array();
		foreach ($data as $constellation) {

			$constellations[] = $constellation->ss_constellation_name;
		}

		return response()->json($constellations);
	}

	public function regions(Request $request)
	{

		$data = SolarSystems::
		where("ss_region_name","LIKE","%{$request->input('query')}%")
		->groupBy('ss_region_name')
		->select('ss_region_name')
		->get();

		$region = array();
		foreach ($data as $region) {

			$regions[] = $region->ss_region_name;
		}

		return response()->json($regions);
	}

	public function corporations(Request $request) {

		$data = Corporations::
		where("corporation_name","LIKE","%{$request->input('query')}%")
		->groupBy('corporation_name')
		->select('corporation_name')
		->get();

		$corporations = array();
		foreach ($data as $corporation) {

			$corporations[] = $corporation->corporation_name;
		}

		return response()->json($corporations);
	}

	public function alliances(Request $request)
	{

		$data = Alliances::
		where("alliance_name","LIKE","%{$request->input('query')}%")
		->groupBy('alliance_name')
		->select('alliance_name')
		->get();

		$alliances = array();
		foreach ($data as $alliance) {

			$alliances[] = $alliance->alliance_name;
		}

		return response()->json($alliances);
	}
	public function alliance_tickers(Request $request)
	{

		$data = Alliances::
		where("alliance_ticker","LIKE","%{$request->input('query')}%")
		->groupBy('alliance_ticker')
		->select('alliance_ticker')
		->get();

		$alliances = array();
		foreach ($data as $alliance) {

			$alliances[] = $alliance->alliance_ticker;
		}

		return response()->json($alliances);
	}

}
