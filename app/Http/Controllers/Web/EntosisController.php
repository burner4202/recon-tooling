<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Vanguard\EntosisCampaigns;
use Vanguard\EntosisScouts;
use Vanguard\EntosisHackers;
use Vanguard\EntosisNodes;
use Vanguard\Corporations;
use Vanguard\Alliances;

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
use Vanguard\ESITokens;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class EntosisController extends Controller
{
	public function campaigns() {

		$event_type = [
			'Offensive' => 'Offensive',
			'Defensive' => 'Defensive',
		];

		$structure_type = [
			//'32458' => 'Infrastructure Hub',
			//'32226' => 'Territorial Claim Unit',
			'Infrastructure Hub' => 'Infrastructure Hub',
			'Territorial Claim Unit' => 'Territorial Claim Unit',
		];

		$availability = [
			'Goonswarm Federation' => 'Goonswarm Federation',
			'Imperium' => 'Imperium',
		];

		$pending_campaigns = EntosisCampaigns::where('ec_status', 0)->orderBy('id', 'DESC')->get();
		$active_campaigns = EntosisCampaigns::where('ec_status', 1)->orderBy('id', 'DESC')->get();
		$completed_campaigns = EntosisCampaigns::where('ec_status', 2)->orderBy('id', 'DESC')->get();

		return view('entosis.campaigns', compact('event_type', 'structure_type', 'availability', 'pending_campaigns', 'active_campaigns', 'completed_campaigns'));

	}

	public function active_campaigns() {

		
		$active_campaigns = EntosisCampaigns::where('ec_status', 1)->get();

		return view('entosis.active_campaigns', compact('active_campaigns'));

	}

	public function view_campaign($id) {

		$user = Auth::id();

		$campaign = EntosisCampaigns::where('ec_campaign_id', $id)->first();

		$systems = SolarSystems::where('ss_constellation_id', $campaign->ec_target_constellation_id)->get();
		$characters = ESITokens::where('esi_user_id', $user)
		->where('esi_active', 1)
		->get();

		$scouts = EntosisScouts::where('es_campaign_id', $id)->get();
		$hackers = EntosisHackers::where('eh_campaign_id', $id)->get();
		$nodes = EntosisNodes::where('en_campaign_id', $id)->get();

		$active_hackers = EntosisHackers::where('eh_campaign_id', $id)->where('eh_status', 1)->get();

		$spawn_systems = ['' => 'Select System'];
		$select_characters = [];
		$select_hacker = ['' => 'Select Hacker'];

		foreach($systems as $each_system) { 
			// We also have a system, so we don't need an if statement as below.
			$spawn_systems[$each_system['ss_system_name']] = $each_system['ss_system_name']; 
		}

		foreach($characters as $character) { 
			// We also have a system, so we don't need an if statement as below.
			$select_characters[$character['esi_character_name']] = $character['esi_character_name']; 
		}

		foreach($active_hackers as $hacker) { 
			// We also have a system, so we don't need an if statement as below.
			$select_hacker[$hacker['eh_character_id']] = $hacker['eh_character_name']; 
		}

		return view('entosis.active_campaign', compact('campaign', 'spawn_systems', 'select_characters', 'scouts', 'hackers', 'select_hacker', 'nodes'));

	}

	public function view_campaign_registered_hackers($id) {

		// Add MiddleWare Route on This!

		$data = EntosisHackers::where('eh_campaign_id', $id)
		->where('eh_status', 1)
		->orderBy('eh_registered_at', 'DESC')
		->get();
		
		return response()->json($data);

	}

	public function view_campaign_registered_scouts($id) {

		// Add MiddleWare Route on This!

		$data = EntosisScouts::where('es_campaign_id', $id)
		->where('es_status', 1)
		->orderBy('es_registered_at', 'DESC')
		->get();
		
		return response()->json($data);

	}

	public function view_campaign_registered_nodes($id) {

		// Add MiddleWare Route on This!

		$data = EntosisNodes::where('en_campaign_id', $id)
		->where('en_node_status', 0)
		->orderBy('en_registered_at', 'DESC')
		->get();
		
		return response()->json($data);

	}

	public function view_campaign_registered_nodes_allocate(Request $request, $id) {

		$hacker = $request->input('select_hacker');
	}

	public function add_campaign_to_pending(Request $request) {

		$user = Auth::user();
		$system = $request->input('system');
		$event_type = $request->input('event_type');
		$structure_type = $request->input('structure_type');
		$availability = $request->input('availability');
		$notes = $request->input('notes');
		$now = Carbon::now()->toDateTimeString();

		if(!$notes) { $notes = ""; }

		// If we have a system, Get the system id and check CCP Data.
		if($system) {

			// Pull System ID from Database
			$system_properties = SolarSystems::where('ss_system_name', $system)
			->first(); 			

			/*
			$table->increments('id');
    		$table->bigInteger('ec_campaign_id');
    		$table->string('ec_target_system');
    		$table->bigInteger('ec_target_system_id');
    		$table->string('ec_target_constellation');
    		$table->bigInteger('ec_target_constellation_id');
    		$table->string('ec_target_region');
    		$table->bigInteger('ec_target_region_id');
    		$table->float('ec_target_adm', 2);
    		$table->string('ec_event_type');
    		$table->string('ec_structure_type');
    		$table->bigInteger('ec_structure_type_id');
    		$table->string('ec_availability');
    		$table->text('ec_notes');
    		$table->datetime('ec_campaign_start_time');
    		$table->datetime('ec_structure_vulnerable_start_time');
    		$table->datetime('ec_structure_vulnerable_end_time');
    		$table->string('ec_campaign_created_by');
    		$table->string('ec_campaign_dispatched_by');
    		$table->datetime('ec_campaign_created_at');
    		$table->datetime('ec_campaign_dispatched_at');
    		$table->integer('ec_status');

    		/* 0 = Pending ready for dispatch
    		 * 1 = Active Campaign
    		 * 2 = Completed Campaign
    		 

    		$table->float('ec_sov_attacker_score', 2);
    		$table->float('ec_sov_defender_score', 2);
    		$table->bigInteger('ec_sov_defender_id');
    		$table->bigInteger('ec_sov_structure_id');
			*/


			// Lets add data we know first.
			// Make a hash for URI Lookup
    		$md5 =  md5($system_properties->ss_system_name . $now);

    		$campaign = new EntosisCampaigns;
    		$campaign->ec_campaign_id								= $md5;
    		$campaign->ec_target_system								= $system_properties->ss_system_name;
    		$campaign->ec_target_system_id							= $system_properties->ss_system_id;
    		$campaign->ec_target_constellation						= $system_properties->ss_constellation_name;
    		$campaign->ec_target_constellation_id					= $system_properties->ss_constellation_id;
    		$campaign->ec_target_region								= $system_properties->ss_region_name;
    		$campaign->ec_target_region_id							= $system_properties->ss_region_id;
    		$campaign->ec_event_type								= $event_type;
    		$campaign->ec_structure_type							= $structure_type;
    		$campaign->ec_availability								= $availability;
    		$campaign->ec_notes										= $notes;
    		$campaign->ec_campaign_created_by						= $user->username;
    		$campaign->ec_campaign_created_at						= $now;
    		$campaign->ec_status									= 0;
    		$campaign->save();
    		

		} // END OF IF

		return redirect()->back()->withSuccess('Done!');
	}


	public function dispatch($id) {

		$user = Auth::user();
		$now = Carbon::now();

		$campaign = EntosisCampaigns::where('id', $id)->first();

		$campaign->ec_status = 1;
		$campaign->ec_campaign_dispatched_by = $user->username;
		$campaign->ec_campaign_dispatched_at = $now;
		$campaign->save();

		return redirect()
		->back()
		->withSuccess('Campaign: ' . $campaign->id . ' dispatched to active campaigns.');
	}

	public function complete($id) {

		$user = Auth::user();
		$now = Carbon::now();

		$campaign = EntosisCampaigns::where('id', $id)->first();

		$campaign->ec_status = 2;
		$campaign->ec_campaign_finished_by = $user->username;
		$campaign->ec_campaign_finished_at = $now;
		$campaign->save();

		return redirect()
		->back()
		->withSuccess('Campaign: ' . $campaign->id . ' has been marked as completed');
	}


	

	/*
	 * PARAM $campaign_id - campaign hash
	 * PARAM $character_id - Character ID to link to ESI Tokens.
	 */

	public function register_scout_to_campaign(Request $request, $campaign_id) {

		$user = Auth::user();
		$character = $request->input('character');
		$now = Carbon::now()->toDateTimeString();

		// Check Campaign ID is valid

		$valid_campaign = EntosisCampaigns::where('ec_campaign_id', $campaign_id)->first();

		if(!$valid_campaign) {

			return redirect()
			->back()
			->withErrors('Your being a prick, do it again and get banned.');

			// TODO BUILD ACTIVITY ABUSE LOG.

		}

		// Check Character is Valid & Has Active Token

		$valid_character = ESITokens::where('esi_name', $character)
		->where('esi_active', 1)
		->first();

		// Check they haven't already registered the character as a scout/hacker

		$check_scout = EntosisScouts::where('es_campaign_id', $campaign_id)
		->where('es_character_id', $valid_character->esi_character_id)
		->first();

		if($check_scout) {

			return redirect()
			->back()
			->withErrors('Your have already registered this character as a scout.');
		}

		if ($valid_character) {

			// Get Alliance
			$alliance = Corporations::where('corporation_corporation_id', $valid_character->esi_corporation_id)
			->join('alliances', 'corporation.corporation_alliance_id', '=', 'alliances.alliance_alliance_id')
			->select(
				'alliance_alliance_id',
				'alliance_name'
			)
			->first();

			// Check if Character is in an alliance.
			if($alliance) {
				$alliance_id =  $alliance->alliance_alliance_id;
				$alliance_name = $alliance->alliance_name;
			} else {
				$alliance_id =  "";
				$alliance_name = "";
			}

			// Get CCP Data, Location/Ship
			$ccp_online = $this->getCharacterOnline($valid_character->esi_character_id,  $valid_character->esi_refresh_token);

			//if($ccp_online->online == 0) {
			//	return redirect()
			//	->back()
			//	->withErrors('You cannot register a character that is offline.');
			//}

			$ccp_location = $this->getCharacterLocation($valid_character->esi_character_id,  $valid_character->esi_refresh_token);
			$ccp_ship = $this->getCharacterShip($valid_character->esi_character_id,  $valid_character->esi_refresh_token);

			$system = SolarSystems::where('ss_system_id', $ccp_location->solar_system_id)->select('ss_system_name', 'ss_system_id')->first();

			$ship = $this->getTypeID($ccp_ship->ship_type_id);

			// Add Database Entry for New Scout
			$register_scout = new EntosisScouts;
			$register_scout->es_campaign_id 				= $campaign_id;
			$register_scout->es_target_system 				= $valid_campaign->ec_target_system;
			$register_scout->es_user_id 					= $user->id;
			$register_scout->es_username 					= $user->username;
			$register_scout->es_character_id 				= $valid_character->esi_character_id;
			$register_scout->es_character_name 				= $valid_character->esi_name;
			$register_scout->es_character_alliance_id 		= $alliance_id;
			$register_scout->es_character_alliance_name 	= $alliance_name;
			$register_scout->es_location_system_id 			= $system->ss_system_id;
			$register_scout->es_location_system_name		= $system->ss_system_name;
			$register_scout->es_ship_type_id				= $ship->type_id;
			$register_scout->es_ship_type_name				= $ship->name;
			$register_scout->es_registered_at				= $now;
			$register_scout->es_status						= 1;
			$register_scout->save();

			return redirect()
			->back()
			->withSuccess('Registered ' . $character . ' as a scout, for this campaign. Please report nodes.');

		} else {

			return redirect()
			->back()
			->withErrors('This is not a valid character, register your ESI tokens.');

		}
		
	}

	public function add_node_to_campaign(Request $request, $campaign_id) {

		$user = Auth::user();
		$node = $request->input('node');
		$spawn_system = $request->input('spawn_system');
		$now = Carbon::now()->toDateTimeString();

		// Check Campaign ID is valid

		$valid_campaign = EntosisCampaigns::where('ec_campaign_id', $campaign_id)->first();

		if(!$valid_campaign) {

			return redirect()
			->back()
			->withErrors('Your being a prick, do it again and get banned.');

			// TODO BUILD ACTIVITY ABUSE LOG.

		}

		if($spawn_system == "") {

			return redirect()
			->back()
			->withErrors('Enter a System');
		}

		$system = SolarSystems::where('ss_system_name', $spawn_system)->select('ss_system_name', 'ss_system_id')->first();

		if($node == "") {

			return redirect()
			->back()
			->withErrors('Enter a Node');
		}

		$check_scout = EntosisScouts::where('es_campaign_id', $campaign_id)
		->where('es_user_id', $user->id)
		->first();

		// Check if scout exists
		if(!$check_scout) {

			return redirect()
			->back()
			->withErrors("You cannot add a node, if you haven't added a scout");
		}

		// Add Node

		$add_node = new EntosisNodes;
		$add_node->en_campaign_id 				= $campaign_id;
		$add_node->en_target_system 			= $valid_campaign->ec_target_system;
		$add_node->en_node_id		 			= $node;
		$add_node->en_added_by_user_id 			= $user->id;
		$add_node->en_added_by_username			= $user->username;

		$add_node->en_node_system_id 			= $system->ss_system_id;
		$add_node->en_node_system_name 			= $system->ss_system_name;

		$add_node->en_registered_at 			= $now;

		$add_node->en_node_status 				= 0;
		$add_node->save();


		return redirect()
		->back()
		->withSuccess('Node ' . $node . ' added to system: ' . $spawn_system);


	}

	public function register_hacker_to_campaign(Request $request, $campaign_id) {

		$user = Auth::user();
		$character = $request->input('character');
		$now = Carbon::now()->toDateTimeString();

		// Check Campaign ID is valid

		$valid_campaign = EntosisCampaigns::where('ec_campaign_id', $campaign_id)->first();

		if(!$valid_campaign) {

			return redirect()
			->back()
			->withErrors('Your being a prick, do it again and get banned.');

			// TODO BUILD ACTIVITY ABUSE LOG.

		}

		// Check Character is Valid & Has Active Token

		$valid_character = ESITokens::where('esi_name', $character)
		->where('esi_active', 1)
		->first();

		// Check they haven't already registered the character as a scout/hacker

		$check_hacker = Entosishackers::where('eh_campaign_id', $campaign_id)
		->where('eh_character_id', $valid_character->esi_character_id)
		->first();

		if($check_hacker) {

			return redirect()
			->back()
			->withErrors('Your have already registered this character as a hacker.');
		}

		if ($valid_character) {

			// Get Alliance
			$alliance = Corporations::where('corporation_corporation_id', $valid_character->esi_corporation_id)
			->join('alliances', 'corporation.corporation_alliance_id', '=', 'alliances.alliance_alliance_id')
			->select(
				'alliance_alliance_id',
				'alliance_name'
			)
			->first();

			// Check if Character is in an alliance.
			if($alliance) {
				$alliance_id =  $alliance->alliance_alliance_id;
				$alliance_name = $alliance->alliance_name;
			} else {
				$alliance_id =  "";
				$alliance_name = "";
			}

			// Get CCP Data, Location/Ship
			$ccp_online = $this->getCharacterOnline($valid_character->esi_character_id,  $valid_character->esi_refresh_token);

			//if($ccp_online->online == 0) {
			//	return redirect()
			//	->back()
			//	->withErrors('You cannot register a character that is offline.');
			//}

			$ccp_location = $this->getCharacterLocation($valid_character->esi_character_id,  $valid_character->esi_refresh_token);
			$ccp_ship = $this->getCharacterShip($valid_character->esi_character_id,  $valid_character->esi_refresh_token);

			$system = SolarSystems::where('ss_system_id', $ccp_location->solar_system_id)->select('ss_system_name', 'ss_system_id')->first();

			$ship = $this->getTypeID($ccp_ship->ship_type_id);

			// Add Database Entry for New Hacker
			$register_scout = new EntosisHackers;
			$register_scout->eh_campaign_id 				= $campaign_id;
			$register_scout->eh_target_system 				= $valid_campaign->ec_target_system;
			$register_scout->eh_user_id 					= $user->id;
			$register_scout->eh_username 					= $user->username;
			$register_scout->eh_character_id 				= $valid_character->esi_character_id;
			$register_scout->eh_character_name 				= $valid_character->esi_name;
			$register_scout->eh_character_alliance_id 		= $alliance_id;
			$register_scout->eh_character_alliance_name 	= $alliance_name;
			$register_scout->eh_location_system_id 			= $system->ss_system_id;
			$register_scout->eh_location_system_name		= $system->ss_system_name;
			$register_scout->eh_ship_type_id				= $ship->type_id;
			$register_scout->eh_ship_type_name				= $ship->name;
			$register_scout->eh_registered_at				= $now;
			$register_scout->eh_status						= 1;
			$register_scout->save();

			return redirect()
			->back()
			->withSuccess('Registered ' . $character . ' as a hacker, for this campaign. Please watch for nodes & waypoints.');

		} else {

			return redirect()
			->back()
			->withErrors('This is not a valid character, register your ESI tokens.');

		}
		
	}


	/// BELOW HERE IS ALL CCP SHIT FOR ENDPOINTS


	public function getSovCampaignsFromCCP($system) 
	{

		// Endpoint: https://esi.evetech.net/ui/#/Sovereignty/get_sovereignty_campaigns

		$configuration = Configuration::getInstance();
		$client_id = config('eve.client_id');
		$secret_key = config('eve.secret_key');


		try {
			$esi = new Eseye();
			$response = $esi->invoke('get', '/sovereignty/campaigns/', []);

		}  catch (EsiScopeAccessDeniedException $e) {
			return redirect()->back()
			->withErrors('SSO Token is invalid');
		} catch (RequestFailedException $e) {
			return redirect()->back()
			->withErrors('Got ESI Error');
		} catch (Exception $e) {
			return redirect()->back()
			->withErrors('ESI is fucked');
		}

		return collect($response);
	}

	public function remove_from_dispatch($id) {

		$campaign = EntosisCampaigns::where('id', $id)->first();

		if($campaign->ec_status == 0) {

			$campaign->destroy($campaign->id);

			return redirect()
			->back()
			->withSuccess('Removed campaign from the pending queue.');

		} else {

			return redirect()
			->back()
			->withErrors('Your cannot remove an active/completed campaign, stop being a dick.');
		}

	}



	public function getSovStructuresFromCCP($system) 
	{

		// Endpoint: https://esi.evetech.net/ui/#/Sovereignty/get_sovereignty_structures

		$configuration = Configuration::getInstance();
		$client_id = config('eve.client_id');
		$secret_key = config('eve.secret_key');


		try {
			$esi = new Eseye();
			$response = $esi->invoke('get', '/sovereignty/structures/', []);

		}  catch (EsiScopeAccessDeniedException $e) {
			return redirect()->back()
			->withErrors('SSO Token is invalid');
		} catch (RequestFailedException $e) {
			return redirect()->back()
			->withErrors('Got ESI Error');
		} catch (Exception $e) {
			return redirect()->back()
			->withErrors('ESI is fucked');
		}

		return collect($response);
	}


	public function getCharacterLocation($character_id, $token) 
	{

		// Endpoint: https://esi.evetech.net/ui/#/Location/get_characters_character_id_location

		$configuration = Configuration::getInstance();
		$client_id = config('eve.client_id');
		$secret_key = config('eve.secret_key');
		$refresh_token = $token;

		$authentication = new EsiAuthentication([
			'client_id'     => $client_id,
			'secret'        => $secret_key,
			'refresh_token' => $refresh_token,
		]);

		try {
			$esi = new Eseye($authentication);
			$response = $esi->invoke('get', '/characters/{character_id}/location/', [
				'character_id' => $character_id,
			]);

		}  catch (EsiScopeAccessDeniedException $e) {



			$updateESIToken = ESITokens::updateOrCreate([
				'esi_character_id'                => $character_id,
			],[
				'esi_active'                      => 0,
			]);

			return redirect()->back()
			->withErrors('SSO Token is invalid');
		} catch (RequestFailedException $e) {

			$updateESIToken = ESITokens::updateOrCreate([
				'esi_character_id'                => $character_id,
			],[
				'esi_active'                      => 0,
			]);

			return redirect()->back()
			->withErrors('Got ESI Error');
		} catch (Exception $e) {
			return redirect()->back()
			->withErrors('ESI is fucked');
		}

		return $response;
	}

	public function getCharacterOnline($character_id, $token) 
	{
		// Endpoint: https://esi.evetech.net/ui/#/Location/get_characters_character_id_online

		$configuration = Configuration::getInstance();
		$client_id = config('eve.client_id');
		$secret_key = config('eve.secret_key');
		$refresh_token = $token;

		$authentication = new EsiAuthentication([
			'client_id'     => $client_id,
			'secret'        => $secret_key,
			'refresh_token' => $refresh_token,
		]);

		try {
			$esi = new Eseye($authentication);
			$response = $esi->invoke('get', '/characters/{character_id}/online/', [
				'character_id' => $character_id,
			]);

		}  catch (EsiScopeAccessDeniedException $e) {

			$updateESIToken = ESITokens::updateOrCreate([
				'esi_character_id'                => $character_id,
			],[
				'esi_active'                      => 0,
			]);

			return redirect()->back()
			->withErrors('SSO Token is invalid');
		} catch (RequestFailedException $e) {

			$updateESIToken = ESITokens::updateOrCreate([
				'esi_character_id'                => $character_id,
			],[
				'esi_active'                      => 0,
			]);

			return redirect()->back()
			->withErrors('Got ESI Error');
		} catch (Exception $e) {
			return redirect()->back()
			->withErrors('ESI is fucked');
		}

		return $response;
	}

	public function getCharacterShip($character_id, $token) 
	{
		// Endpoint: https://esi.evetech.net/ui/#/Location/get_characters_character_id_ship

		$configuration = Configuration::getInstance();
		$client_id = config('eve.client_id');
		$secret_key = config('eve.secret_key');
		$refresh_token = $token;

		$authentication = new EsiAuthentication([
			'client_id'     => $client_id,
			'secret'        => $secret_key,
			'refresh_token' => $refresh_token,
		]);

		try {
			$esi = new Eseye($authentication);
			$response = $esi->invoke('get', '/characters/{character_id}/ship/', [
				'character_id' => $character_id,
			]);

		}  catch (EsiScopeAccessDeniedException $e) {

			$updateESIToken = ESITokens::updateOrCreate([
				'esi_character_id'                => $character_id,
			],[
				'esi_active'                      => 0,
			]);
			return redirect()->back()
			->withErrors('SSO Token is invalid');
		} catch (RequestFailedException $e) {

			$updateESIToken = ESITokens::updateOrCreate([
				'esi_character_id'                => $character_id,
			],[
				'esi_active'                      => 0,
			]);
			return redirect()->back()
			->withErrors('Got ESI Error');
		} catch (Exception $e) {
			return redirect()->back()
			->withErrors('ESI is fucked');
		}

		return $response;
	}

	public function getSolarSystem($system_id)
	{
		// SolarSystems Model
		$system = SolarSystems::where('ss_system_id', $system_id)->first();
		return $system;
	}

	public function getTypeID($type_id)
	{
	// endpoint: https://esi.evetech.net/ui/#/Universe/get_universe_types_type_id


		$configuration = Configuration::getInstance();
		$client_id = config('eve.client_id');
		$secret_key = config('eve.secret_key');

		try {
			$esi = new Eseye();
			$response = $esi->invoke('get', '/universe/types/{type_id}/', [
				'type_id' => $type_id,
			]);

		}  catch (EsiScopeAccessDeniedException $e) {
			return redirect()->back()
			->withErrors('SSO Token is invalid');
		} catch (RequestFailedException $e) {
			return redirect()->back()
			->withErrors('Got ESI Error');
		} catch (Exception $e) {
			return redirect()->back()
			->withErrors('ESI is fucked');
		}

		return $response;
	}

	public function getEveRoute($destination, $origin, $token)
	{
		// endpoint: https://esi.evetech.net/ui/#/Routes/get_route_origin_destination
		// requires: destination, origin (system_id)

		$configuration = Configuration::getInstance();
		$client_id = config('eve.client_id');
		$secret_key = config('eve.secret_key');

		try {
			$esi = new Eseye();
			$response = $esi->invoke('get', '/route/{origin}/{destination}/', [
				'origin' => $origin,
				'destination' => $destination,
			]);

		}  catch (EsiScopeAccessDeniedException $e) {

			return redirect()->back()
			->withErrors('SSO Token is invalid');
		} catch (RequestFailedException $e) {

			return redirect()->back()
			->withErrors('Got ESI Error');
		} catch (Exception $e) {
			return redirect()->back()
			->withErrors('ESI is fucked');
		}

		return $response;
	}

	public function formatEveDate($date) {
		$trimmed = rtrim($date, "Z");
		$dateAndTime = explode("T", $trimmed);
		$dt = Carbon::parse($dateAndTime[0] . " " . $dateAndTime[1]);   
		return $dt;   
	}



}
