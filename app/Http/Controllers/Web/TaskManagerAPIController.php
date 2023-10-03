<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Datetime;

use Auth;
use Log;
use DB;
use Input;
use Carbon\Carbon;
use Vanguard\User;
use Vanguard\SolarSystems;
use Vanguard\Regions;
use Vanguard\Constellations;
use Vanguard\TaskManager;
use Vanguard\APICalls;


class TaskManagerAPIController extends Controller
{

	public function postTask(Request $request) {

		$token = null;
		$user_agent = null;
		$ip_address = null;
		$endpoint = "/task/add";
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
			## Validate Body

			$user = "System";
			$system = $request->input('system');
			$tasks = $request->input('task');
			$username = $request->input('username');
			$prority = "Right Now!";
			$notes = "Request from System";
			$now = Carbon::now()->toDateTimeString();

			# Do we have system body?

			if($tasks) {
				$task_note = 'Scan ' . $tasks;
			} else {
				$task_note = "Scan System";
			}

			if($username) {
				$requested_username = $username;
			} else {
				$requested_username = "System";
			}


			if($system) { 
				$system_properties = SolarSystems::where('ss_system_name', $system)
				->first(); 

				# Does the System Name Exist

				if($system_properties) {

					# Do we have a task for this system

					$exists = TaskManager::where('tm_solar_system_name', $system_properties->ss_system_name)
					->where('tm_state', '<', 3)
					->where('tm_state', '>', 0)
					->first();

					if($exists) {
						return response()->json('System Name already has an outstanding task.', 200);
					}

					$endpoint = "/task/add/" . $system_properties->ss_system_name;

					$task = new TaskManager;
					$task->tm_created_by_user_id 			= 0;
					$task->tm_created_by_user_username 		= $user;
					$task->tm_solar_system_id 				= $system_properties->ss_system_id;
					$task->tm_solar_system_name				= $system_properties->ss_system_name;
					$task->tm_constellation_id				= $system_properties->ss_constellation_id;
					$task->tm_constellation_name			= $system_properties->ss_constellation_name;
					$task->tm_region_id						= $system_properties->ss_region_id;
					$task->tm_region_name					= $system_properties->ss_region_name;
					$task->tm_task							= $task_note;
					$task->tm_prority						= $prority;
					$task->tm_notes							= $notes;
					$task->tm_created_datetime_at			= $now;
					$task->tm_state							= 1; 
					$task->save();

					# Add to API Log
					$this->recordAPIRequest($ip_address, $user_agent, $endpoint, $gsf_user, '200');

					$content = 'SCAN REQUEST: ' . $system_properties->ss_system_name . ' of ' . $system_properties->ss_region_name . ' :STRUCTURE: ' . $task_note . ' requested by ' . $requested_username;
					$this->postToJabber($content);

					# Posted Task Respond
					return response()->json("Task Created", 201);

				} else {
					# Add to API Log
					$this->recordAPIRequest($ip_address, $user_agent, $endpoint, $gsf_user, '404');
					# Not Found.
					return response()->json('System Name does not exist.', 400);
				}
			} else {
					# Add to API Log
				$this->recordAPIRequest($ip_address, $user_agent, $endpoint, $gsf_user, '404');
					# Not Found.
				return response()->json('System Name is Empty.', 400);
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



	public function postToJabber($content) {

		$channel = 'recon@conference.goonfleet.com';
       			 # I don't care about errors.
		$client = new \GuzzleHttp\Client(['http_errors' => false]);

		$options = [
			'channel' => $channel,
			'payload' => $content,
		];

		$url = "https://recon-bot.7rqtwti-zm2ubuph7uwzs.apps.gnf.lt/api/webhook";
		$request = $client->post($url, ['body' => json_encode($options) ]);

	}
}
