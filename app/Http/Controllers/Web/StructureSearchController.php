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

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class StructureSearchController extends Controller
{
    # Index
	public function index() {

		$search = Input::input('search');

		if(!$search) {
			$search = "-";
		} 

		$query = KnownStructures::query();

		if ($search) {
			$query->where(function ($q) use ($search) {
				$q->sortable();
				$q->where('str_name', "like", "{$search}");
			});
		} 

		$structures = $query
		->sortable()
		->orderBy('updated_at', 'DESC')
		->where('str_destroyed', 0)
		->paginate(1);

		if ($search) {
			$structures->appends(['search' => $search]);
		}

		return view('structures.search', compact('structures'));

	}
}
