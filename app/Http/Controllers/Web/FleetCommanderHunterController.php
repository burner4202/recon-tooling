<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Vanguard\Characters;
use Vanguard\AllianceStandings;
use Input;

class FleetCommanderHunterController extends Controller
{
    public function index() {

    	# Filter Standings for < 0

    	$alliance_standings = AllianceStandings::where('as_contact_type', 'alliance')
    	->where('as_standing', '>', '0.01')
    	->pluck('as_alliance_name')
    	->toArray();

     	$search = Input::input('search');

		$standings = 		[
			'' => 'All',
			'Friendly' => 'Friendly',
			'Neutral' => 'Neutral',
			'Hostile' => 'Hostile',
		];

		$alliance = ['' => 'All'];

		$alliances = Characters::where('monitor', 1)->groupBy('character_alliance_name')->orderBY('character_alliance_name', 'ASC')->get();

		foreach($alliances as $each_alliance) { 
			if($each_alliance['character_alliance_name'] == "") {
				// DO Nothing & Don't fucking overright my blank array!
			} else {
				$alliance[$each_alliance['character_alliance_name']] = $each_alliance['character_alliance_name']; 
			}
		}

		$input_standings = Input::input('standings');
		$input_alliance = Input::input('alliance');

		$query = Characters::query();

		if ($input_alliance) {
			$query->where('character_alliance_name', $input_alliance);
		}

		if ($input_standings == "Friendly") {
			$query->where('as_standing', '>', 0.00);
		}

		if ($input_standings == "Hostile") {
			$query->where('as_standing', '<', 0.00);
		}

		if ($input_standings == "Neutral") {
			$query->where('as_standing', null);
		}

		if ($search) {
			$query->where(function ($q) use ($search) {
				$q->where('character_name', "like", "%{$search}%");
				$q->orWhere('character_corporation_name', 'like', "%{$search}%");
				$q->orWhere('character_alliance_name', 'like', "%{$search}%");
			});
		}

		$fleet_commanders = $query
		->sortable()
		->where('monitor', 1)
		->whereNotIn('character_alliance_name', $alliance_standings)
    	->leftjoin('alliance_standings', 'characters.character_character_id', '=', 'alliance_standings.as_contact_id')
    	->orderBy('character_name')
		->paginate(500);

		if ($search) {
			$fleet_commanders->appends(['search' => $search]);
		}
		
    	return view('fc_hunter.index', compact('fleet_commanders', 'standings', 'alliance'));
    }
}
