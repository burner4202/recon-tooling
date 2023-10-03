<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Vanguard\AllianceStandings;
use Vanguard\Corporations;
use Vanguard\Alliances;
use Vanguard\Characters;

use Input;

class AllianceStandingsController extends Controller
{
	public function index() {

		$search = Input::input('search');

		$query = AllianceStandings::query();

		if ($search) {
			$query->where(function ($q) use ($search) {
				$q->sortable();
				$q->where('as_character_name', "like", "%{$search}%");
				$q->orWhere('as_corporation_name', 'like', "%{$search}%");
				$q->orWhere('as_alliance_name', 'like', "%{$search}%");
				$q->orWhere('as_standing', 'like', "%{$search}%");
			});
		}

		$standings = $query
		->sortable()
		->orderBy('as_standing', 'DESC')
		->paginate(20);

		if ($search) {
			$standings->appends(['search' => $search]);
		}
		
		return view('standings.index', compact('standings'));
	}
}
