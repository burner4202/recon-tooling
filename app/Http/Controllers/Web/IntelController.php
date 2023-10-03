<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Input;


class IntelController extends Controller
{

	public function index() {

		return view('intel.index');
	}

	public function post()
	{

		$data = array();
		$store = array();
		$count = 0;
		$local = array();

		$request = Input::all();

		if($request['title'] == null) {
			return redirect()->back()
			->withErrors('Fill the box you dick.'); 
		}

		$dscan = explode("\n", $request['title']);

		dd($dscan);

		foreach($dscan as $index => $line) {

			$trimmed = rtrim($line, "\r");
	


			/*

			if(count($which_one) == 1) {

				# scopehone
				# This should be a local paste , do local stuff

				#testing, build a local array

				return redirect()->back()
				->withSuccess('Local Scan');


			} elseif (count($which_one) == 4) {


				# 28352	RUBBISH Rorqual	-
				# This should be a dscan, do dscan stuff.

				return redirect()->back()
				->withSuccess('DSCAN');

			} elseif(count($which_one) == 5) {

				# This is fleet comp.
				#scopehone	PDE-U3	Malediction	Interceptor	Fleet Commander (Boss)	0 - 0 - 5	

			} else {

				# Return fail page with vardump.

				return redirect()->back()
				->withErrors('Fail');


			}

			*/
		}

		// Return

	}

	public function validStructure($structure_id) {


		$structures = [

    		//Fortizars
			'35832',
			'35833',
			'35834',

    		// Faction Fortizars
			'47512',
			'47513',
			'47514',
			'47515',
			'47516',
			'40340',

    		// Engineering
			'35825',
			'35826',
			'35827',
			'35840',

    		// StructuresCyno Etc
			'35841',
			'35841',
			'37534',

    		// Moon
			'35835',
			'35836',

		];

		if (in_array($structure_id, $structures)) {
			return true;
		} else {
			return false;
		}
	}

}
