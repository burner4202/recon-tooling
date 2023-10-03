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

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;


class CorporationsController extends Controller
{
	public function index() {

		$search = Input::input('search');
		$query = Corporations::query();

		if ($search) {
			$query->where(function ($q) use ($search) {
				$q->sortable();
				$q->where('corporation_name', "like", "%{$search}%");
				$q->orWhere('corporation_ticker', 'like', "%{$search}%");

			});
		}

		/*
		"id" => 1
          "corporation_id" => 98014536
          "alliance_id" => 1354830081
          "ceo_id" => 887625289
          "creator_id" => 240070320
          "date_founded" => "2010-06-01T05:36:00Z"
          "description" => ""
          "member_count" => 26
          "corp_name" => "Jarhead Industries"
          "tax_rate" => "0.15"
          "ticker" => "CONDI"
          "url" => ""
          "created_at" => "2019-05-20 16:02:43"
          "updated_at" => "2019-05-20 16:02:43"
          "creator_corporation_id" => 459299583
          "executor_corporation_id" => 1344654522
          "name" => "Goonswarm Federation"
          */

		$corporations = $query->sortable()
		->join('alliances', 'corporation.corporation_alliance_id', '=', 'alliances.alliance_alliance_id')
		//->join('known_structures', 'corporation.corporation_id', '=', 'known_structures.owner_corporation_id')
		->paginate(50, ['*'], '#corporations');

		if ($search) {
			$corporations->appends(['search' => $search]);
		}

		return view('corporations.index', compact('corporations'));

	}

	public function autocomplete(Request $request)
	{
		$data = Corporations::where("corporation_name","LIKE","%{$request->input('query')}%")
		->get();

		return response()->json($data);
	}



	public function view($corporation_id) {

		$corporation = Corporations::where('corporation_corporation_id', $corporation_id)
		->first();

		$alliance = Alliances::where('alliance_alliance_id', $corporation->corporation_alliance_id)
		->first();

		$search = Input::input('search');
		$query = KnownStructures::query();

		if ($search) {
			$query->where(function ($q) use ($search) {
				$q->sortable();
				$q->where('str_name', "like", "%{$search}%");
				$q->orWhere('str_type', "like", "%{$search}%");
				$q->orWhere('str_system', "like", "%{$search}%");
				$q->orWhere('str_state', "like", "%{$search}%");
			});
		}

		$structures = $query->where('str_owner_corporation_name', $corporation->corporation_name)
		->sortable()
		->where('str_destroyed', 0)
		->paginate(100);

		if ($search) {
			$structures->appends(['search' => $search]);
		}


		return view('corporations.view', compact('corporation', 'structures', 'alliance'));

	}
}
