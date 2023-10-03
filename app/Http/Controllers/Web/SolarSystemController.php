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
use Route;
use Carbon\Carbon;
use Vanguard\User;
use Vanguard\SolarSystems;
use Vanguard\Regions;
use Vanguard\Constellations;
use Vanguard\TypeIDs;
use Vanguard\NPCKills;
use Vanguard\KnownStructures;
use Vanguard\ActivityTracker;
use Vanguard\TaskManager;
use Vanguard\SystemCostIndices;
use Vanguard\SovStructures;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class SolarSystemController extends Controller
{

    /**
     * Display all regions & stats
     *
     * @return Response
    */
    public function universe()
    {

    	$search = Input::input('search');
    	$query = SolarSystems::query();

    	if ($search) {
    		$query->where(function ($q) use ($search) {
    			$q->sortable();
    			$q->where('ss_system_name', "like", "%{$search}%");
    			$q->orWhere('ss_region_name', 'like', "%{$search}%");
    			$q->orWhere('ss_constellation_name', 'like', "%{$search}%");

    		});
    	}

    	$universe = $query
    	->sortable()
    	->orderBy('ss_system_name')
    	->paginate(30);

    	//Ansiblex Jump Gate
    	//Tenebrex Cyno Jammer
    	$cyno_jammer = KnownStructures::where('str_type', 'Tenebrex Cyno Jammer')
    	->where('str_destroyed', 0)
    	->get();

    	$jump_gate = KnownStructures::where('str_type', 'Ansiblex Jump Gate')
    	->where('str_destroyed', 0)
    	->get();

    	$keepstars = KnownStructures::where('str_type', 'Keepstar')
    	->where('str_destroyed', 0)
    	->get();

    	$sotiyos = KnownStructures::where('str_type', 'Sotiyo')
    	->where('str_destroyed', 0)
    	->get();

    	$tataras = KnownStructures::where('str_type', 'Tatara')
    	->where('str_destroyed', 0)
    	->get();

    	$structures = KnownStructures::where('str_destroyed', 0)
    	->get();

    	if ($search) {
    		$universe->appends(['search' => $search]);
    	}

    	$regions = SolarSystems::groupBy('ss_region_id')
    	->orderBy('ss_region_name', 'ASC')
    	->get();

    	return view('solar.universe', compact('universe', 'regions', 'cyno_jammer', 'jump_gate', 'structures', 'keepstars', 'sotiyos', 'tataras'));

    }


    /**
     * Display all regions & stats
     *
     * @return Response
    */
    public function region($region_id)
    {
    	/*
    	$systems = SolarSystems::where('region_id', $region_id)
    	->orderBy('system_name', 'ASC')
    	->get();

    	return view('solar.region', compact('systems'));
    	*/

    	$region_name = SolarSystems::where('ss_region_id', $region_id)->first();

    	$search = Input::input('search');
    	$query = SolarSystems::query();

    	if ($search) {
    		$query->where(function ($q) use ($search) {
    			$q->sortable();
    			$q->where('ss_system_name', "like", "%{$search}%");
    			$q->orWhere('ss_region_name', 'like', "%{$search}%");
    			$q->orWhere('ss_constellation_name', 'like', "%{$search}%");

    		});
    	}

    	$systems = $query->where('ss_region_id', $region_id)->orderBy('ss_system_name')->sortable()->paginate(50);

    	if ($search) {
    		$systems->appends(['search' => $search]);
    	}

        	//Ansiblex Jump Gate
    	//Tenebrex Cyno Jammer
    	$cyno_jammer = KnownStructures::where('str_type', 'Tenebrex Cyno Jammer')
    	->where('str_destroyed', 0)
    	->get();

    	$jump_gate = KnownStructures::where('str_type', 'Ansiblex Jump Gate')
    	->where('str_destroyed', 0)
    	->get();

    	$keepstars = KnownStructures::where('str_type', 'Keepstar')
    	->where('str_destroyed', 0)
    	->get();

    	$sotiyos = KnownStructures::where('str_type', 'Sotiyo')
    	->where('str_destroyed', 0)
    	->get();

    	$tataras = KnownStructures::where('str_type', 'Tatara')
    	->where('str_destroyed', 0)
    	->get();

    	$structures = KnownStructures::where('str_destroyed', 0)
    	->get();


    	return view('solar.region', compact('region_name', 'systems', 'cyno_jammer', 'jump_gate', 'structures', 'keepstars', 'sotiyos', 'tataras'));

    }

        /**
     * Display all regions & stats
     *
     * @return Response
    */
        public function constellation($constellation_id)
        {
    	/*
    	$systems = SolarSystems::where('region_id', $region_id)
    	->orderBy('system_name', 'ASC')
    	->get();

    	return view('solar.region', compact('systems'));
    	*/

    	$constellation_name = SolarSystems::where('ss_constellation_id', $constellation_id)->first();
    	

    	$search = Input::input('search');
    	$query = SolarSystems::query();

    	if ($search) {
    		$query->where(function ($q) use ($search) {
    			$q->sortable();
    			$q->where('ss_system_name', "like", "%{$search}%");
    			$q->orWhere('ss_constellation_name', 'like', "%{$search}%");

    		});
    	}

    	$systems = $query->where('ss_constellation_id', $constellation_id)->orderBy('ss_system_name')->sortable()->paginate(50);

    	if ($search) {
    		$systems->appends(['search' => $search]);
    	}

    	//Ansiblex Jump Gate
    	//Tenebrex Cyno Jammer
    	$cyno_jammer = KnownStructures::where('str_type', 'Tenebrex Cyno Jammer')
    	->where('str_destroyed', 0)
    	->get();

    	$jump_gate = KnownStructures::where('str_type', 'Ansiblex Jump Gate')
    	->where('str_destroyed', 0)
    	->get();

    	$keepstars = KnownStructures::where('str_type', 'Keepstar')
    	->where('str_destroyed', 0)
    	->get();

    	$sotiyos = KnownStructures::where('str_type', 'Sotiyo')
    	->where('str_destroyed', 0)
    	->get();

    	$tataras = KnownStructures::where('str_type', 'Tatara')
    	->where('str_destroyed', 0)
    	->get();

    	$structures = KnownStructures::where('str_destroyed', 0)
    	->get();

    	return view('solar.constellation', compact('constellation_name', 'systems', 'cyno_jammer', 'jump_gate', 'structures', 'keepstars', 'sotiyos', 'tataras'));

    }

    public function system($system_id)
    {

    	$systemDetails = SolarSystems::where('ss_system_id', $system_id)
    	->first();

    	//$structures = KnownStructures::where('system_id', $system_id) 
    	//->orderBy('type', 'ASC')
    	//->get();

    	$search = Input::input('search');
    	$input_per_page = Input::input('no_per_page');
    	$query = KnownStructures::query();

    	if ($search) {
    		$query->where(function ($q) use ($search) {
    			$q->sortable();
    			$q->where('str_name', "like", "%{$search}%");
    			$q->orWhere('str_type', "like", "%{$search}%");
    			$q->orWhere('str_owner_corporation_name', "like", "%{$search}%");

    		});
    	}


    	$no_per_page = 	['50' => '50', '100' => '100'];

    	if($input_per_page == "") { 
    		$per_page = 50; 
    	} elseif($input_per_page > 100) { 
    		$per_page = 50; 
    	} else {
    		$per_page = $input_per_page; 
    	}


    	$structures = $query
    	->sortable()
    	->where('str_system_id', $system_id)
    	->where('str_destroyed' , 0)
    	->orderBy('str_name')
    	->paginate($per_page, ['*'], 'structures');

    	if ($search) {
    		$structures->appends(['search' => $search]);
    	}

    	$actions = ActivityTracker::where('at_system_id', $system_id)
    	->where('at_structure_name', '=', "")
    	->orderBy('created_at','DESC')
    	->paginate(5, ['*'], 'activity');

    	$totalStructures = KnownStructures::where('str_system_id', $system_id)
    	->where('str_destroyed' , 0)
    	->count();

    	$tasks = TaskManager::where('tm_solar_system_id', $system_id)
    	->where('tm_state', 1)
    	->orderBy('created_at', 'DESC')->get();

    	$my_tasks = TaskManager::where('tm_solar_system_id', $system_id)
    	->where('tm_accepted_by_user_id', Auth::id())
    	->where('tm_state', 2)
    	->orderBy('created_at', 'DESC')->get();

    	$historyM = array();
    	$historyRTE = array();
    	$historyRME = array();
    	$historyC = array();
    	$historyI = array();
    	$historyR = array();

    	$to = Carbon::today()->format('Y-m-d');   

    	$from = Carbon::today()->subMonth(6)->format('Y-m-d');  

    	$indicesHistory = SystemCostIndices::where('sci_solar_system_id', $system_id) 
    	->whereBetween('sci_date', [$from, $to])
    	//->orderBy('date', 'ASC')
    	->get();

    	foreach($indicesHistory as $value) {

    		$historyM[$value->sci_date] = $value->sci_manufacturing;
    		$historyRTE[$value->sci_date] = $value->sci_researching_time_efficiency;
    		$historyRME[$value->sci_date] = $value->sci_researching_material_efficiency;
    		$historyC[$value->sci_date] = $value->sci_copying;
    		$historyI[$value->sci_date] = $value->sci_invention;
    		$historyR[$value->sci_date] = $value->sci_reaction;

    	}

        # Sov Structures

        $sov_structures = SovStructures::where('solar_system_id', $system_id)->get();

    	return view('solar.system', compact('systemDetails', 'structures', 'system_id', 'actions', 'totalStructures', 'tasks', 'my_tasks', 'historyM', 'historyRTE', 'historyRME', 'historyC', 'historyI', 'historyR', 'no_per_page', 'sov_structures'));

    }



    public function getSolarSystem($system_id)
    {

    	$system = SolarSystems::where('ss_system_id', $system_id)
    	->first();

    	return $system['ss_system_name'];

    }

    public function getRegionName($region_id)
    {

    	if($region_id == 0) {
    		return "Orphen";
    	}  else {


        // Get Ledger for User

    		$region = Regions::where('ss_region_id', $region_id)
    		->first();
    	}

    	return $region['region_name'];

    }



    public function dscanindex() {

    	return view('dscan.index');
    }


    public function storeDscan($system_id)
    {

    	$structures = array();
    	$store = array();
    	$count = 0;

    	$request = Input::all();

    	if($request['title'] == null) {
    		return redirect()->back()
    		->withErrors('Fill the box you dick.'); 
    	}

    	$dscan = explode("\n", $request['title']);

    	foreach($dscan as $index => $structure) {

    		$trimmed = rtrim($structure, "\r");
    		$structures[] = preg_split("/[\t]/", $trimmed);



    		if(count($structures[$index]) < 4) {
    			return redirect()->back()
    			->withErrors('Invalid Dscan Parse');
    		}



    		if($this->validStructure($structures[$index][0])) {

    			$md5 = md5($system_id . $structures[$index][0] . $structures[$index][1] . $structures[$index][2]);

    			$solarSystem = $this->getSolarSystem($system_id);

    			$system_explode = explode(" - ", $structures[$index][1]);

    			// CCP are gay and fucked this code up with jump bridges.
    			/*
    			array:2 [▼
				0 => "T5ZI-S » QY1E-N"
	 			1 => "Eye of Terror Mk III"
				]
				*/

				if(preg_match('/\b » \b/', $system_explode[0])) { 
					$system_name_split = preg_split('/\b » \b/', $system_explode[0]);
					$system_name = $system_name_split[0];
				} else {
					$system_name = $system_explode[0];
				}
				

				//if($solarSystem != $system_name) {
				//	return redirect()->back()
				//	->withErrors('This is the wrong system you jackass. Or... There is a unanchored FREE citadel in system!');
				//}

				$system = SolarSystems::where('ss_system_id', $system_id)->first();

				$structure_size = $this->structureSize($structures[$index][0]);

				$count += 1;

				## Check  if it exists 

				$exists = KnownStructures::where('str_structure_id_md5', $md5)->first();

				# If it doesn't exist... add it along with a cored status.

				if(!$exists) {

					$addStructure = KnownStructures::updateOrCreate([
					'str_structure_id_md5'  => $md5,
				],[
					'str_system_id'     	 => $system_id,
					'str_type_id'     		 => $structures[$index][0],
					'str_name'      		 => $structures[$index][1],
					'str_type'    			 => $structures[$index][2],
					'str_distance'   		 => $structures[$index][3],
					'str_system'			 => $solarSystem,
					'str_constellation_id' 	 => $system->ss_constellation_id,
					'str_constellation_name' => $system->ss_constellation_name,
					'str_region_id'			 => $system->ss_region_id,
					'str_region_name'		 => $system->ss_region_name,
					'str_size'				 => $structure_size,
					'str_cored'				 => "Yes",

				]);

				} else {

				$addStructure = KnownStructures::updateOrCreate([
					'str_structure_id_md5'  => $md5,
				],[
					'str_system_id'     	 => $system_id,
					'str_type_id'     		 => $structures[$index][0],
					'str_name'      		 => $structures[$index][1],
					'str_type'    			 => $structures[$index][2],
					'str_distance'   		 => $structures[$index][3],
					'str_system'			 => $solarSystem,
					'str_constellation_id' 	 => $system->ss_constellation_id,
					'str_constellation_name' => $system->ss_constellation_name,
					'str_region_id'			 => $system->ss_region_id,
					'str_region_name'		 => $system->ss_region_name,
					'str_size'				 => $structure_size

				]);
			}


				$system->ss_empty = "";
				$system->save();



			}
		}

		if($count > 0) {

    	// Add Action to Activity Log
			$user_id = Auth::id();
			$action = 'Added or Updated ' . $count . ' Structures';
			$this->addActivityLogToSystem($user_id, $system_id, $system_name, $action);


			return redirect()->back()
			->withSuccess('Updated Solar System with ' . $count . ' Structures');
		}

		return redirect()->back()
		->withErrors('No Structures Found');

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

	public function structureSize($structure_type) {


		$structures = [

    		//Fortizars
			'35832' => 'Medium',
			'35833' => 'Large',
			'35834' => 'Extra Large',

    		// Faction Fortizars
			'47512' => 'Large',
			'47513' => 'Large',
			'47514' => 'Large',
			'47515' => 'Large',
			'47516' => 'Large',
			'40340' => 'Extra Large',

    		// Engineering
			'35825' => 'Medium',
			'35826' => 'Large',
			'35827' => 'Extra Large',


    		// StructuresCyno Etc
			'35840' => 'FLEX',
			'35841' => 'FLEX',
			'37534' => 'FLEX',

    		// Moon

			'35835' => 'Medium',
			'35836' => 'Large',

		];

		if(isset($structures[$structure_type]))
		{
			return $structures[$structure_type];
		}

	}


	public function addActivityLogToSystem($user_id, $system_id, $system_name, $user_action) {

		$user = User::where('id', $user_id)
		->first();

		$action = new ActivityTracker;
		$action->at_user_id = $user->id;
		$action->at_username = $user->username;
		$action->at_system_id = $system_id;
		$action->at_system_name = $system_name;
		$action->at_action = $user_action;
		$action->save();
	}

	public function system_empty($system_id)
	{

		$user_id = Auth::id();

		$system = SolarSystems::where('ss_system_id', $system_id) 
		->first();

		$system->ss_empty = "Empty";
		$system->save();
		$action = $system->ss_system_name . ' marked as empty.';
		$this->addActivityLogToSystem($user_id, $system->ss_system_id, $system->ss_system_name, $action);


		return redirect()->route('solar.system', $system->ss_system_id)
		->withSuccess($system->ss_system_name . " marked as an emtpy system.");
	}

}


