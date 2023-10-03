<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\ADMWatch;
use Vanguard\SovStructures;

use Vanguard\SolarSystems;
use Vanguard\Regions;
use Vanguard\Constellations;
use Vanguard\User;
use Vanguard\APICalls;

class ADMWatchAPIController extends Controller
{

	public function top10(Request $request) {

		$token = null;
		$user_agent = null;
		$ip_address = null;
		$endpoint = "/api/adm/top10";
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

			## Gets 10 shit ADMs

			$systems = ADMWatch::where('adm_state', 1)
			->orderBy('vulnerability_occupancy_level', 'ASC')
			->where('adm_state', 1)
			->where('vulnerability_occupancy_level', '>', 0)
			->join('sov_structures', 'adm_watch.adm_system_id', '=', 'sov_structures.solar_system_id')
			->where('structure_type_name', 'Infrastructure Hub')
			->take(10)
			->get();

			if($systems) {
				# Add to API Log
				$this->recordAPIRequest($ip_address, $user_agent, $endpoint, $gsf_user, '200');
				# Found the Structure Name, Respond.
				return response()->json($systems, 200);
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

}
