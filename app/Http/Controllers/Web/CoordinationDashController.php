<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Auth;
use Input;
use Carbon\Carbon;
use Vanguard\User;
use Vanguard\ESITokens;
use Vanguard\SolarSystems;
use Vanguard\TypeIDs;
use Vanguard\KnownStructures;
use Vanguard\Alliances;
use Vanguard\Corporations;
use Vanguard\CoordActiveFleets;
use Vanguard\WatchedSystems;


use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;


class CoordinationDashController extends Controller
{
	public function index() {

		$systems = WatchedSystems::orderBy('solar_system_name', 'ASC')
		->get();

		return view('coordination.index', compact('systems'));

	}

	public function add_system(Request $request) {

		$system = $request->input('system');

		if($system) { 
			$system_properties = SolarSystems::where('ss_system_name', $system)
			->first(); 

			if($system_properties) {

			// Add one System task for Pending

				$new_system = new WatchedSystems;
				$new_system->solar_system_id 					= $system_properties->ss_system_id;
				$new_system->solar_system_name					= $system_properties->ss_system_name;
				$new_system->constellation_id					= $system_properties->ss_constellation_id;
				$new_system->constellation_name					= $system_properties->ss_constellation_name;
				$new_system->region_id							= $system_properties->ss_region_id;
				$new_system->region_name						= $system_properties->ss_region_name;
				$new_system->save();

				return redirect()
				->back();
				//->withSuccess('Added ' . $system . ' to watch.');

			} else {

				return redirect()
				->back();
				//->withErrors('This system does not exist');

			}

		}
	}

	public function remove_system($system_id) {

		$system = WatchedSystems::where('solar_system_id', $system_id)
		->first(); 

		if($system) {

			$system->delete();

			return redirect()
			->back();
			//->withSuccess('Removed ' . $system->ss_system_name . ' from the watch list');

		} else {

			return redirect()
			->back();
			//->withErrors('This system does not exist');

		}

	}


}
