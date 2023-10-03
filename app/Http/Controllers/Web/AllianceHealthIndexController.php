<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Vanguard\AllianceHealthIndex;
use Vanguard\Alliances;

use Carbon\Carbon;

class AllianceHealthIndexController extends Controller
{
	public function index() {

		$now = Carbon::now();

		$health = AllianceHealthIndex::where('date', '=', $now->format('Y-m-d'))
		->get();

		return view('alliance_health.index', compact('health'));
	}

	public function view($alliance_id) {
    	## Alliance Health Index

    	$alliance = Alliances::where('alliance_alliance_id', $alliance_id)->first();

		$health = array();
		$average_adm = array();
		$ihub_count = array();

		$to = Carbon::today()->format('Y-m-d');   

		$from = Carbon::today()->subMonth(6)->format('Y-m-d');  

		$healthHistory = AllianceHealthIndex::where('alliance_id', $alliance_id) 
		->whereBetween('date', [$from, $to])
    	//->orderBy('date', 'ASC')
		->get();

		foreach($healthHistory as $value) {

			$health[$value->date] = $value->health;

		}

		foreach($healthHistory as $value) {

			$ihub_count[$value->date] = $value->ihub_count;

		}

		foreach($healthHistory as $value) {

			$average_adm[$value->date] = $value->average_adm;

		}

		return view('alliance_health.view', compact('health', 'ihub_count', 'average_adm', 'alliance'));

	}
}
