<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Vanguard\ADMWatch;
use Vanguard\SovStructures;

use Vanguard\SolarSystems;
use Vanguard\Regions;
use Vanguard\Constellations;

class ADMWatchController extends Controller
{

	public function index() {

		$watching_systems = ADMWatch::where('adm_state', 1)
		->orderBy('adm_system_name', 'ASC')
		->join('sov_structures', 'adm_watch.adm_system_id', '=', 'sov_structures.solar_system_id')
		->where('structure_type_name', 'Infrastructure Hub')
		->paginate(500);

		return view('adm_watch.index', compact('watching_systems'));

	}

	public function manage() {

		$pending_systems = ADMWatch::where('adm_state', 0)
		->orderBy('adm_system_name', 'ASC')
		->join('sov_structures', 'adm_watch.adm_system_id', '=', 'sov_structures.solar_system_id')
		->where('structure_type_name', 'Infrastructure Hub')
		->paginate(500, ['*'], 'pending_systems');

		$watching_systems = ADMWatch::where('adm_state', 1)
		->orderBy('adm_system_name', 'ASC')
		->join('sov_structures', 'adm_watch.adm_system_id', '=', 'sov_structures.solar_system_id')
		->where('structure_type_name', 'Infrastructure Hub')
		->paginate(500, ['*'], 'outstanding');

		return view('adm_watch.manage', compact('pending_systems', 'watching_systems'));



	}

	public function add_to_pending(Request $request) {

		$system = $request->input('system');
		$constellation = $request->input('constellation');
		$region = $request->input('region');

		if($system) { 
			$system_properties = SolarSystems::where('ss_system_name', $system)
			->first(); 

			// Add one System task for Pending

			$addSystem = ADMWatch::updateOrCreate([
				'adm_system_id' 				 => $system_properties->ss_system_id
			],[
				'adm_system_name'     			 => $system_properties->ss_system_name,
				'adm_constellation_id'     		 => $system_properties->ss_constellation_id,
				'adm_constellation_name'    	 => $system_properties->ss_constellation_name,
				'adm_region_id'     			 => $system_properties->ss_region_id,
				'adm_region_name'     			 => $system_properties->ss_region_name,
				'adm_state'     				 => 0,
			]);

			return redirect()
			->back()
			->withSuccess('Added ' . $system . ' system to tasks for pending and review/acceptance.');

		}

		if($constellation) { 
			$constellation_properties = SolarSystems::where('ss_constellation_name', $constellation)
			->get(); 

			// Add all systems for constellation

			foreach($constellation_properties as $system) {

			// Add one System task for Pending

				$addSystem = ADMWatch::updateOrCreate([
					'adm_system_id' 				 => $system->ss_system_id
				],[
					'adm_system_name'     			 => $system->ss_system_name,
					'adm_constellation_id'     		 => $system->ss_constellation_id,
					'adm_constellation_name'    	 => $system->ss_constellation_name,
					'adm_region_id'     			 => $system->ss_region_id,
					'adm_region_name'     			 => $system->ss_region_name,
					'adm_state'     				 => 0,
				]);


			}

			return redirect()
			->back()
			->withSuccess('Added ' . count($constellation_properties) . ' systems to tasks for pending and review/acceptance.');

		}


		if($region) { 
			$region_properties = SolarSystems::where('ss_region_name', $region)
			->get(); 	

			// Add all systems for constellation

			foreach($region_properties as $system) {

			// Add one System task for Pending

				$addSystem = ADMWatch::updateOrCreate([
					'adm_system_id' 				 => $system->ss_system_id
				],[
					'adm_system_name'     			 => $system->ss_system_name,
					'adm_constellation_id'     		 => $system->ss_constellation_id,
					'adm_constellation_name'    	 => $system->ss_constellation_name,
					'adm_region_id'     			 => $system->ss_region_id,
					'adm_region_name'     			 => $system->ss_region_name,
					'adm_state'     				 => 0,
				]);



			}

			return redirect()
			->back()
			->withSuccess('Added ' . count($region_properties) . ' systems to tasks for pending and review/acceptance.');

		}

	}


	public function dispatch($system_id) {

		$system = ADMWatch::where('adm_system_id', $system_id)->first();

		$system->adm_state = 1;
		$system->save();

		return redirect()
		->back()
		->withSuccess($system->adm_system_name . ' has been added to the ADM watch list.');

	}

	public function dispatch_all() {


		$system = ADMWatch::where('adm_state', 0)->get();

		$amount = $system->count();

		foreach ($system as $dispatch) {

			$add_system = ADMWatch::where('adm_system_id', $dispatch->adm_system_id)->first();
			$add_system->adm_state = 1;
			$add_system->save();
		}

		return redirect()
		->back()
		->withSuccess('Added ' . $amount . ' systems to the ADM Watchlist.');

	}

	public function remove($system_id) {

		$remove_system = ADMWatch::where('adm_system_id', $system_id)->first();
		$remove_system->delete();

		return redirect()
		->back()
		->withSuccess('Removed ' . $remove_system->adm_system_name . ' from the queue');

	}

	public function remove_all() {

		$system = ADMWatch::where('adm_state', 0)->get();

		$amount = $system->count();

		foreach ($system as $delete) {

			$remove_system = ADMWatch::where('adm_system_id', $delete->adm_system_id)->first();
			$remove_system->delete();
		}

		return redirect()
		->back()
		->withSuccess('Removed ' . $amount . ' systems from the pending queue.');

	}

}
