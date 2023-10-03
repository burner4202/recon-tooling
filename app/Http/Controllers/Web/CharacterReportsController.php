<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Vanguard\Characters;
use Vanguard\CharacterReport;

use Input;

class CharacterReportsController extends Controller
{
	public function index() {

		$search = Input::input('search');

		$alliance = ['' => 'All'];
		$hull_type = ['' => 'All'];

		$alliances = CharacterReport::groupBy('alliance_name')->orderBY('alliance_name', 'ASC')->get();

		foreach($alliances as $each_alliance) { 
			if($each_alliance['alliance_name'] == "") {
				// DO Nothing & Don't fucking overright my blank array!
			} else {
				$alliance[$each_alliance['alliance_name']] = $each_alliance['alliance_name']; 
			}
		}

		$hulls = CharacterReport::groupBy('hull_type')->orderBY('hull_type', 'ASC')->get();

		foreach($hulls as $each_hull) { 
			if($each_hull['hull_type'] == "") {
				// DO Nothing & Don't fucking overright my blank array!
			} else {
				$hull_type[$each_hull['hull_type']] = $each_hull['hull_type']; 
			}
		}

		$input_alliance = Input::input('alliance');
		$input_hull = Input::input('hull_type');
		$input_character = Input::input('character');

		$query = CharacterReport::query();

		if ($input_alliance) {
			$query->where('alliance_name', $input_alliance);
		}

		if ($input_hull) {
			$query->where('hull_type', $input_hull);
		}

		if ($input_character) {
			$query->where('character_name', $input_character);
		}


		if ($search) {
			$query->where(function ($q) use ($search) {
				$q->where('character_name', "like", "%{$search}%");
				$q->orWhere('corporation_name', 'like', "%{$search}%");
				$q->orWhere('system_name', 'like', "%{$search}%");
				$q->orWhere('region_name', 'like', "%{$search}%");
				$q->orWhere('alliance_name', 'like', "%{$search}%");
				$q->orWhere('hull_type', 'like', "%{$search}%");
				$q->orWhere('ship_hull_id', 'like', "%{$search}%");
			});
		}

		$reports = $query
		->sortable()
		->orderBy('created_at', 'DESC')
		->paginate(100);

		if ($search) {
			$reports->appends(['search' => $search]);
		}

		return view('character_reports.index', compact('reports', 'hull_type', 'alliance'));
	}
}
