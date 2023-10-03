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
use Vanguard\Alliances;
use Vanguard\Corporations;
use Vanguard\SovStructures;
use Vanguard\AllianceStandings;
use Vanguard\GroupDossier;
use Vanguard\Stagers;
use Vanguard\AllianceHealthIndex;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class AllianceController extends Controller
{
	public function index() {

		$search = Input::input('search');
		$query = Alliances::query();

		if ($search) {
			$query->where(function ($q) use ($search) {
				$q->sortable();
				$q->where('alliance_name', "like", "%{$search}%");
				$q->orWhere('alliance_ticker', 'like', "%{$search}%");

			});
		}

		$alliances = $query->sortable()->paginate(50);

		if ($search) {
			$alliances->appends(['search' => $search]);
		}

		return view('alliances.index', compact('alliances'));

	}

	public function autocomplete(Request $request)
	{
		$data = Alliances::
		where("alliance_name","LIKE","%{$request->input('query')}%")
		->get();

		return response()->json($data);
	}



	public function view($alliance_id) {

		$alliance = Alliances::where('alliance_alliance_id', $alliance_id)
		->first();

		$corporations = Corporations::where('corporation_alliance_id', $alliance_id)
		->sortable()
		->paginate(100, ['*'], 'corporations');

		$totalCount = Corporations::where('corporation_alliance_id', $alliance_id)
		->get();

		$totalCorporations = count($totalCount);

		$structures = KnownStructures::where('str_owner_alliance_id', $alliance_id)
		->get();

		$home_region = KnownStructures::where('str_owner_alliance_id', $alliance_id)
		->where('str_destroyed', 0)
		->where('str_type', 'Keepstar')
		->orderBy('str_value', 'DESC')
		->first();

		$total_alive = KnownStructures::where('str_owner_alliance_id', $alliance_id)
		->where('str_destroyed', 0)
		->get();

		$total_destroyed = KnownStructures::where('str_owner_alliance_id', $alliance_id)
		->where('str_destroyed', 1)
		->get();

		$keepstars = KnownStructures::where('str_owner_alliance_id', $alliance_id)
		->where('str_type', "Keepstar")
		->where('str_destroyed', 0)
		->orderBy('str_value', 'DESC')
		->take(10)
		->get();

		$soytios = KnownStructures::where('str_owner_alliance_id', $alliance_id)
		->where('str_type', "Sotiyo")
		->where('str_destroyed', 0)
		->orderBy('str_value', 'DESC')
		->take(10)
		->get();

		$azbels = KnownStructures::where('str_owner_alliance_id', $alliance_id)
		->where('known_structures.str_type', "Azbel")
		->where('str_destroyed', 0)
		->orderBy('str_value', 'DESC')
		->take(10)
		->get();

		$sov = SovStructures::where('alliance_name', $alliance->alliance_name)->get();

		$upwell_structures = $this->upwellDistPerType($alliance_id);
		// Convert Object to Array
		$upwell_value = array();
		foreach($upwell_structures as $value) {$upwell_value[$value->str_type] = $value->str_value; }

		$query = [
			'Package Delivered',
			'Package Removed'
		];

		# Run Query to get the goodies.
		//$results = KnownStructures::where('str_owner_alliance_id', $alliance_id)
		/*->whereIn('at_action', $query)
		->selectRaw(
			'
		COUNT( (CASE WHEN str_type = "Package Delivered" THEN at_action END) ) AS packages_delivered,
			COUNT( (CASE WHEN at_action = "Package Removed" THEN at_action END) ) AS packages_removed,
			DAY(created_at) as day
			')
		->groupBy('day')
		->orderBy('day', 'asc')
		->get();
		

		# Init Chart Array
		$chart = array();

		foreach($results as $account) {
			$chart[$account->day] = [
				'packages_delivered' => $account->packages_delivered,
				'packages_removed' => $account->packages_removed,
				'day' => $account->day,
			]; 
		} 
		*/

		$alliance_fcs = AllianceStandings::where('as_alliance_name', $alliance->alliance_name)
		->where('as_corporation_id', '>', 0)
		->where('as_standing', '<', 0) # We do not want to show any characters on friendly pages with a postive standing.
		->orderBy('as_character_name', 'ASC')
		->get();

		$dossiers = GroupDossier::where('target_alliance_name', $alliance->alliance_name)
		->where('state', 2)
		->orderBy('updated_at', 'DESC')
		->get();

		$associated_groups = GroupDossier::where('target_alliance_name', $alliance->alliance_name)
		->where('state', 2)
		->groupBy('corporation_name')
		->get();

		$stagers = Stagers::where('alliance_id', $alliance_id)
		->orderBy('solar_system_name')
		->get();



	

		return view('alliances.view', compact(
			'alliance',
			'corporations',
			'totalCorporations',
			'structures',
			'keepstars',
			'soytios',
			'azbels',
			'upwell_value',
			'structures',
			'home_region',
			'total_alive',
			'total_destroyed',
			'sov',
			'alliance_fcs',
			'dossiers',
			'associated_groups',
			'stagers'
		));

	}

	public function upwellDistPerType($alliance_id)
	{

		/*
		#items: array:8 [▼
	    0 => {#1143 ▼
	      +"str_type": "Ansiblex Jump Gate"
	      +"str_value": 0.0
	    }
	    1 => {#1145 ▼
	      +"str_type": "Astrahus"
	      +"str_value": 2500000000.0
	    }
	    2 => {#1190 ▼
	      +"str_type": "Azbel"
	      +"str_value": 50730541825.96
	    }
	    3 => {#1144 ▼
	      +"str_type": "Fortizar"
	      +"str_value": 21940789255.02
	    }
	    4 => {#1146 ▼
	      +"str_type": "Keepstar"
	      +"str_value": 134563172879.55
	    }
	    5 => {#1142 ▼
	      +"str_type": "Pharolux Cyno Beacon"
	      +"str_value": 0.0
	    }
	    6 => {#1147 ▼
	      +"str_type": "Raitaru"
	      +"str_value": 330987332.95
	    }
	    7 => {#1141 ▼
	      +"str_type": "Tatara"
	      +"str_value": 102142709152.71
	    }
	    ]


	    array:8 [▼
		  "Ansiblex Jump Gate" => 0.0
		  "Astrahus" => 2500000000.0
		  "Azbel" => 50730541825.96
		  "Fortizar" => 21940789255.02
		  "Keepstar" => 134563172879.55
		  "Pharolux Cyno Beacon" => 0.0
		  "Raitaru" => 330987332.95
		  "Tatara" => 102142709152.71
		]

	    */

		$upwell_structures = DB::table('known_structures')
		->join('corporation', 'known_structures.str_owner_corporation_id', '=', 'corporation.corporation_corporation_id')
		->join('alliances', 'corporation.corporation_alliance_id', '=', 'alliances.alliance_alliance_id')
		->where('alliance_alliance_id', $alliance_id)
		->select(DB::raw('str_type as str_type'), DB::raw('sum(str_value) as str_value'))
		->groupBy('str_type')
		->orderBy('str_type')
		->get();

		return $upwell_structures;
	}

}
