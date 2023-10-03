<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Input;

use Vanguard\AllianceEnemyStandings;

class AllianceEnemyStandingsController extends Controller
{
    public function index() {

    	$alliances = AllianceEnemyStandings::groupBy('as_enemy_alliance_id')->get();

    	return view('alliances_enemy.index', compact('alliances'));
    }

    	public function view($alliance_id) {

		$search = Input::input('search');

		$query = AllianceEnemyStandings::query();

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
		->where('as_enemy_alliance_id', $alliance_id)
		->paginate(100);

		if ($search) {
			$standings->appends(['search' => $search]);
		}

		$alliance = AllianceEnemyStandings::where('as_enemy_alliance_id', $alliance_id)->first();
		
		return view('alliances_enemy.view', compact('standings', 'alliance'));
	}
}
