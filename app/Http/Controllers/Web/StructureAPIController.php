<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\KnownStructures;
use Vanguard\APICalls;
use Vanguard\User;

class StructureAPIController extends Controller
{
	public function singleStructure(Request $request, $structure_name) {

		$token = null;
		$user_agent = null;
		$ip_address = null;
		$endpoint = "/structure/" . $structure_name;
		$gsf_user = "";

		$headers = $request->headers;

		//dd($headers);

		# Get token From Header.
		foreach($headers as $index => $header) {
			## Get token Header
			if($index == "token") {
				$token = $header[0];
			}

			if($index == "x-real-ip" || $index == "x-client-ip") {
				$ip_address = $header[0];
			}

			if($index == "user-agent") {
				$user_agent = $header[0];
			}

			if($index == "x-gsf-user") {
				$gsf_user = $header[0];
			}

			## Get API Key Header.
			## Do API Stuff
		}

		$users = User::all()->pluck('email')->toArray();

		# Check we have a token header.
		if($token == "3aa2bd23a26bf2a95ef4a14df9b3fb7a") {

			## Check User Permissions Here
			if(!in_array($gsf_user, $users)) {
				# Add to API Log
				$this->recordAPIRequest($ip_address, $user_agent, $endpoint, $gsf_user, '401');
				# Go away.
				return response()->json('Unauthorized, Invalid Username', 401);
			} 
			## Search for the Structure
			## If Structure ID is presented.

			if(is_numeric($structure_name)) {
				$structure = KnownStructures::where('str_structure_id', $structure_name)
				->where('str_destroyed', 0)
				->orderBy('updated_at', 'DESC')
				->first();
			} else {
				$structure = KnownStructures::where('str_name', $structure_name)
				->where('str_destroyed', 0)
				->first();
			}

			if($structure) {
				# Add to API Log
				$this->recordAPIRequest($ip_address, $user_agent, $endpoint, $gsf_user, '200');
				# Found the Structure Name, Respond.
				return response()->json($structure, 200);
			} else {
				# Add to API Log
				$this->recordAPIRequest($ip_address, $user_agent, $endpoint, $gsf_user, '404');
				# Not Found.
				return response()->json('Error, Structure Not Found', 404);
			}

		} else {
			# Add to API Log
		    $this->recordAPIRequest($ip_address, $user_agent, $endpoint, $gsf_user, '401');
			# Go away.
			return response()->json('Unauthorized, Invalid Token', 401);

		}

	}

	public function destroyedStructures(Request $request) {

		$token = null;
		$user_agent = null;
		$ip_address = null;
		$endpoint = "/destroyed/structures";
		$gsf_user = "";

		$headers = $request->headers;

		//dd($headers);

		# Get token From Header.
		foreach($headers as $index => $header) {
			## Get token Header
			if($index == "token") {
				$token = $header[0];
			}

			if($index == "x-real-ip" || $index == "x-client-ip") {
				$ip_address = $header[0];
			}

			if($index == "user-agent" ) {
				$user_agent = $header[0];
			}

			if($index == "x-gsf-user") {
				$gsf_user = $header[0];
			}


			## Get API Key Header.
			## Do API Stuff
		}

		$users = User::all()->pluck('email')->toArray();

		# Check we have a token header.
		if($token == "3aa2bd23a26bf2a95ef4a14df9b3fb7a") {

			## Check User Permissions Here

			if(!in_array($gsf_user, $users)) {
				# Add to API Log
				$this->recordAPIRequest($ip_address, $user_agent, $endpoint, $gsf_user, '401');
				# Go away.
				return response()->json('Unauthorized, Invalid Username', 401);
			} 

			## Get last 40 destroyed structures.

			$structure = KnownStructures::where('str_destroyed', 1)
			->orderBy('updated_at', 'DESC')
			->take(40)
			->select('str_name', 'str_type', 'str_owner_corporation_name', 'str_owner_alliance_name', 'str_value', 'str_system', 
				'str_constellation_name', 'str_region_name', 'str_size', 'str_t2_rigged', 'str_supercapital_shipyard', 'str_capital_shipyard', 'updated_at')
			->get();

			if($structure) {
				# Add to API Log
			    $this->recordAPIRequest($ip_address, $user_agent, $endpoint, $gsf_user, '200');
				# Found the Structure Name, Respond.
				return response()->json($structure, 200);
			}

		} else {
			# Add to API Log
			$this->recordAPIRequest($ip_address, $user_agent, $endpoint, $gsf_user, '401');
			# Go away.
			return response()->json('Unauthorized, Invalid Token', 401);

		}

	}

	public function hostileStructuresInRegion(Request $request, $region_id) {

		$token = null;
		$user_agent = null;
		$ip_address = null;
		$endpoint = "/structures/hostile/region/" . $region_id;
		$gsf_user = "";

		$headers = $request->headers;

		//dd($headers);

		# Get token From Header.
		foreach($headers as $index => $header) {
			## Get token Header
			if($index == "token") {
				$token = $header[0];
			}

			if($index == "x-real-ip" || $index == "x-client-ip") {
				$ip_address = $header[0];
			}

			if($index == "user-agent" ) {
				$user_agent = $header[0];
			}

			if($index == "x-gsf-user") {
				$gsf_user = $header[0];
			}


			## Get API Key Header.
			## Do API Stuff
		}

		$users = User::all()->pluck('email')->toArray();

		# Check we have a token header.
		if($token == "3aa2bd23a26bf2a95ef4a14df9b3fb7a") {

			## Check User Permissions Here

			if(!in_array($gsf_user, $users)) {
				# Add to API Log
				$this->recordAPIRequest($ip_address, $user_agent, $endpoint, $gsf_user, '401');
				# Go away.
				return response()->json('Unauthorized, Invalid Username', 401);
			} 

			## Get last 40 destroyed structures.

			$structure = KnownStructures::where('str_destroyed', 0)
			->where('str_region_id', $region_id)
			->where('str_standings', '<=', 0.00)
			->pluck('str_structure_id');


			if($structure) {
				# Add to API Log
			    $this->recordAPIRequest($ip_address, $user_agent, $endpoint, $gsf_user, '200');
				# Found the Structure Name, Respond.
				return response()->json($structure, 200);
			}

		} else {
			# Add to API Log
			$this->recordAPIRequest($ip_address, $user_agent, $endpoint, $gsf_user, '401');
			# Go away.
			return response()->json('Unauthorized, Invalid Token', 401);

		}

	}

	public function hitlist(Request $request) {

		$token = null;
		$user_agent = null;
		$ip_address = null;
		$endpoint = "/hitlist/";
		$gsf_user = "";

		$headers = $request->headers;

		//dd($headers);

		# Get token From Header.
		foreach($headers as $index => $header) {
			## Get token Header
			if($index == "token") {
				$token = $header[0];
			}

			if($index == "x-real-ip" || $index == "x-client-ip") {
				$ip_address = $header[0];
			}

			if($index == "user-agent") {
				$user_agent = $header[0];
			}

			if($index == "x-gsf-user") {
				$gsf_user = $header[0];
			}

			## Get API Key Header.
			## Do API Stuff
		}

		$users = User::all()->pluck('email')->toArray();

		# Check we have a token header.
		if($token == "3aa2bd23a26bf2a95ef4a14df9b3fb7a") {

			## Check User Permissions Here
			if(!in_array($gsf_user, $users)) {
				# Add to API Log
				$this->recordAPIRequest($ip_address, $user_agent, $endpoint, $gsf_user, '401');
				# Go away.
				return response()->json('Unauthorized, Invalid Username', 401);
			} 
			## Search for the Structure
			## If Structure ID is presented.

			$structure = KnownStructures::where('str_destroyed', 0)
			->where('str_hitlist', 1)
			->orderBy('updated_at', 'DESC')
			->get();

			if($structure) {
				# Add to API Log
				$this->recordAPIRequest($ip_address, $user_agent, $endpoint, $gsf_user, '200');
				# Found the Structure Name, Respond.
				return response()->json($structure, 200);
			} else {
				# Add to API Log
				$this->recordAPIRequest($ip_address, $user_agent, $endpoint, $gsf_user, '404');
				# Not Found.
				return response()->json('Error, Structure Not Found', 404);
			}

		} else {
			# Add to API Log
		    $this->recordAPIRequest($ip_address, $user_agent, $endpoint, $gsf_user, '401');
			# Go away.
			return response()->json('Unauthorized, Invalid Token', 401);

		}

	}





	public function recordAPIRequest($ip, $agent, $endpoint, $gsf_user, $response) {

		$log = new APICalls;
		$log->apc_ip_address = $ip;
		$log->apc_user_agent = $agent;
		$log->apc_endpoint = $endpoint;
		$log->apc_gsf_username = $gsf_user;
		$log->apc_response = $response;
		$log->save();
	}

		public function apiLog() {

		$months = APICalls::selectRaw(
			'
			YEAR(created_at) year, 
			MONTHNAME(created_at) month, 
			COUNT(*) calls, 
			COUNT( (CASE WHEN apc_response = "200" THEN apc_response END) ) AS success,
			COUNT( (CASE WHEN apc_response = "404" THEN apc_response END) ) AS error,
			COUNT( (CASE WHEN apc_response = "401" THEN apc_response END) ) AS unauthorized
			')
		->groupBy('year', 'month')
		->orderBy('created_at', 'desc')
		->get();

		$chart_months = $months->reverse();
		# Init Chart Array
		$chart = array();

		# Make Stacked Chart Array from Data for ChartJS.
		foreach($chart_months as $month) {

			$chart[$month->month . "-" . $month->year] = [
				'calls' => $month->calls,
				'success' => $month->success,
				'error' => $month->error,
				'unauthorized' => $month->unauthorized
			];

		}


		return view('api.index', compact('months', 'chart'));
	}
}
