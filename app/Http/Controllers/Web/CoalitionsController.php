<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Vanguard\Coalitions;
use Vanguard\CoalitionsBake;
use Vanguard\Alliances;
use Vanguard\Corporations;
use Auth;

class CoalitionsController extends Controller
{

	public function list() {

		$coalitions = CoalitionsBake::orderBy('coalition_name', 'ASC')->groupBy('coalition_name')->get();

		$chart = array();
		# Make Stacked Chart Array from Data for ChartJS.
		foreach($coalitions as $coalition) {

			$alliance_count = CoalitionsBake::where('coalition_id', $coalition->coalition_id)->groupBy('alliance_name')->get();
			$corporation_count = CoalitionsBake::where('coalition_id', $coalition->coalition_id)->groupBy('corporation_name')->get();
			$corporation_member_count = CoalitionsBake::where('coalition_id', $coalition->coalition_id)->sum('corporation_member_count');

			

			$chart[$coalition->coalition_name] = [
				'coalition_id' => $coalition->coalition_id,
				'name' => $coalition->coalition_name,
				'alliance_count' => count($alliance_count),
				'corporation_count' => count($corporation_count),
				'corporation_member_count' => $corporation_member_count,
			];		

		}

		return view('coalitions.list', compact('chart'));
	}

		public function view_coalition($id) {

		$alliances = CoalitionsBake::where('coalition_id', $id)->orderBy('alliance_name')->groupBy('alliance_name')->get();

		$corporations = CoalitionsBake::where('coalition_id', $id)->orderBy('corporation_name')->get();

		$coalition = CoalitionsBake::where('coalition_id', $id)->first();

		return view('coalitions.view_coalition', compact('alliances', 'coalition', 'corporations'));
	}

	
	public function manage_index() {

		$coalitions = Coalitions::orderby('name', 'ASC')->get();

		$alliances = Alliances::where('alliance_coalition', '>', 0)->get();
		
		return view('coalitions.index', compact('coalitions', 'alliances'));
		//return view('coalitions.index');
	}

	public function create(Request $request) {

		$user = Auth::user();
		$name = $request->input('name');
		$notes = $request->input('notes');

		if($name) {

		// Add one System task for Pending

			$coalition = new Coalitions;
			$coalition->name 			= $name;
			$coalition->added_by		= $user->username;
			$coalition->save();

			return redirect()
			->back()
			->withSuccess('Added Coalition');

		} else {
			return redirect()
			->back()
			->withErrors('Need a name..');
		}

		
	}

	public function delete($id) {

		$coalition = Coalitions::where('id', $id)->first();

		if($coalition) {

			$coalition->delete();

			return redirect()
			->back()
			->withSuccess('Removed Coalition');
		} else {
			return redirect()
			->back()
			->withErrors('This Coalition does not exist');
		}
	}


	public function view($id) {

		$coalition = Coalitions::where('id', $id)->first();

		$alliances = Alliances::where('alliance_coalition', $id)
		->orderBy('alliance_name', 'ASC')
		->get();
		
		return view('coalitions.view', compact('coalition', 'alliances'));
		//return view('coalitions.index');
	}

	public function add_alliance(Request $request, $id) {

		# Get Coalition Data

		$coalition = Coalitions::where('id', $id)->first();

		if(!$coalition) {
			return redirect()->back()->withErrors('Coalition does not exist');
		}

		$input_alliance = $request->input('alliance');

		# Check Alliance exists

		$alliance = Alliances::where('alliance_name', $input_alliance)->first();

		if(!$alliance) {
			return redirect()->back()->withErrors('Alliance does not exists');
		} 

		# Add Coalition to Alliance

		$alliance->alliance_coalition = $id;
		$alliance->save();

		return redirect()->back()->withSuccess('Added ' . $alliance->alliance_name . ' to ' . $coalition->name);
	}

	public function remove_alliance($alliance) {

		#Check Alliance Exists

		$check_alliance = Alliances::where('alliance_alliance_id', $alliance)->first();

		if($check_alliance) {

			$check_alliance->alliance_coalition = "";
			$check_alliance->save();

			return redirect()
			->back()
			->withSuccess('Removed Alliance this Coalition');
		} else {
			return redirect()
			->back()
			->withErrors('Alliance does not exist');
		}
	}

}
