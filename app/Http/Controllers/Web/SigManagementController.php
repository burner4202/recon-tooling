<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Input;
use DB;

use Vanguard\User;
use Vanguard\SigManagementScouts;

class SigManagementController extends Controller
{
	public function index() {

		$search = Input::input('search');

		$query = SigManagementScouts::query();

		if ($search) {
			$query->where(function ($q) use ($search) {
				$q->sortable();
				$q->where('name', "like", "%{$search}%");
				#$q->orWhere('registered', 'like', "%{$search}%");
			});
		}

		$scouts = $query
		->sortable()
		->orderBy('name', 'ASC')
		->paginate(500);

		if ($search) {
			$scouts->appends(['search' => $search]);
		}


		return view('sig_management.index', compact('scouts'));


	}



	public function import_scouts() {


		$request = Input::all();

		if($request['title'] == null) {
			return redirect()->back()
			->withErrors('Fill the box you dick.'); 
		}

		# Set all records to inactive before updating

		$affected = DB::table('sig_management_scouts')->update(array('active' => 0));

		$lines = explode("\r\n", $request['title']);

		foreach ($lines as $line) {
			
			$goodies = explode("\t", $line);

			if($goodies[7] !== "Fleet Ops (90d)") {

				# We have a member, add them.
				# Meh, check we have 8 items in the array to sort of validate the data eh.

				if(count($goodies) == 8) {

					## Lets do some stuff here.
					## Ideally dispatch to a job, once code is proven.

					$gice_name = $this->goonfleet_name($goodies[0]);
					$registered_on_rt = $this->registered_on_rt($gice_name);

					if($registered_on_rt) {
						$query_user = User::where('username', $gice_name)->first();
						$user_id = $query_user->id;
					} else {
						$user_id = 0;
					}


					# Name\tCheck-in\tLast Mumble\tLast Jabber\tLast Forums\tFleet Ops (30d)\tFleet Ops (60d)\tFleet Ops (90d)\r\n
   					# The Mittani\t\t2020 Apr 20\t2020 Apr 22\t2020 Apr 04\t0\t0\t0\r\n

					$create = SigManagementScouts::updateOrCreate([
						'name' 						 => $gice_name,
					],[
						'user_id'					 => $user_id,
						'check_in'     				 => $goodies[1],
						'active'     				 => 1,
						'registered_on_rt'     		 => $registered_on_rt,
					]);

				} else {

					## Return an error as syntax is incorrect.

					return redirect()->back()
					->withErrors('Parse Error, Invalid Syntax speak to scopeh.'); 

				}
			}
		}

		return redirect()->back()
		->withSuccess('Imported'); 
	}

	private function goonfleet_name($name) {

		$data = strtolower(preg_replace('/\s+/', '_', $name));

		return $data;
	}

	private function registered_on_rt($name) {

		$username = User::where('username', $name)->first();

		if($username) {
			return 1;
		}

		return 0;

	}



}
