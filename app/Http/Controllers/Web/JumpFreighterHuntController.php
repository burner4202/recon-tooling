<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Vanguard\Characters;
use Input;

class JumpFreighterHuntController extends Controller
{
       public function index() {

     	$search = Input::input('search');

		$query = Characters::query();

		if ($search) {
			$query->where(function ($q) use ($search) {
				$q->where('character_name', "like", "%{$search}%");
				$q->orWhere('character_corporation_name', 'like', "%{$search}%");
				$q->orWhere('character_alliance_name', 'like', "%{$search}%");
			});
		}

		$jump_freighters = $query
		->sortable()
		->where('jump_freighter', 1)
    	->orderBy('character_name')
		->paginate(500);

		if ($search) {
			$jump_freighters->appends(['search' => $search]);
		}

    	return view('jump_freighter.index', compact('jump_freighters'));
    }
}
