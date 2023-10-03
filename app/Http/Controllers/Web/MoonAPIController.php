<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Vanguard\NewMoons;
use Vanguard\APICalls;

class MoonAPIController extends Controller
{



	public function singleMoon(Request $request, $moon_name) {

		$token = null;
		$user_agent = null;
		$ip_address = null;
		$endpoint = '/moon/' . $moon_name ;
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

		# Check we have a token header.
		if($token == "1ff163e19a984c43d6159bf5e65c8d6f") {

			

			## Check User Permissions Here
			if($gsf_user !== "rc2") {
				# Add to API Log
				$this->recordAPIRequest($ip_address, $user_agent, $endpoint, $gsf_user, '401');
				# Go away.
				return response()->json('Unauthorized, Invalid Username', 401);
			} 
			## Search for the Structure
			## If Structure ID is presented.

			if(is_numeric($moon_name)) {
				$moon = NewMoons::where('moon_id', $moon_name)
				->first();
			} else {
				$moon = NewMoons::where('moon_name', $moon_name)
				->first();
			}

			if($moon) {
				# Add to API Log
				$this->recordAPIRequest($ip_address, $user_agent, $endpoint, $gsf_user, '200');
				# Found the Moon Name, Respond.

				$result = collect([
					'moon_name' => $moon->moon_name,
					'moon_id' => $moon->moon_id,
					'system_name' => $moon->moon_system_name,
					'constellation_name' => $moon->moon_constellation_name,
					'region_name' => $moon->moon_region_name,
					'rarity' => $moon->moon_r_rating,
					'extraction_value_56_day' => $moon->moon_value_24_hour * 56,
					'last_updated' => $moon->updated_at,
					'dna' => json_decode($moon->moon_dist_ore),
					'goo' => [
						'atmospheric_gases' => $moon->moon_atmo_gases,
						'cadmium' => $moon->moon_cadmium,
						'caesium' => $moon->moon_caesium,
						'chromium' => $moon->moon_chromium,
						'cobalt' => $moon->moon_cobalt,
						'dysprosium' => $moon->moon_dysprosium,
						'evaporite_deposits' => $moon->moon_eva_depo,
						'hafnium' => $moon->moon_hafnium,
						'hydrocarbons' => $moon->moon_hydrocarbons,
						'mercury' => $moon->moon_mercury,
						'neodymium' => $moon->moon_neodymium,
						'platinum' => $moon->moon_platinum,
						'promethium' => $moon->moon_promethium,
						'scandium' => $moon->moon_scandium,
						'silicates' => $moon->moon_silicates,
						'technetium' => $moon->moon_technetium,
						'thulium' => $moon->moon_thulium,
						'titanium' => $moon->moon_titanium,
						'tungsten' => $moon->moon_tungsten,
						'vanadium' => $moon->moon_vanadium,
					],



				]);	

				return response()->json($result, 200);
			} else {
				# Add to API Log
				$this->recordAPIRequest($ip_address, $user_agent, $endpoint, $gsf_user, '404');
				# Not Found.
				return response()->json('Error, Moon Not Found', 404);
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

		$apiLog = APICalls::orderBy('created_at', 'DESC')->get();

		return response()->json($apiLog, 200);
	}
}
