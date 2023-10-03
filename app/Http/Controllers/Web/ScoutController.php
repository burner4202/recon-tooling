<?php

/*
 * Goonswarm Federation Recon Tools
 *
 * Developed by scopehone <scopeh@gmail.com>
 * In conjuction with Natalya Spaghet & Mindstar Technology 
 *
 */

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Socialite;
use Auth;
use Input;
use Carbon\Carbon;
use Vanguard\User;
use Vanguard\ESITokens;
use Vanguard\SolarSystems;
use Vanguard\TypeIDs;
use Vanguard\KnownStructures;
use Vanguard\Alliances;
use Vanguard\Corporations;
use Vanguard\ScoutLocationShipOnline;


use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class ScoutController extends Controller
{
     /**
     * ScoutController constructor.
     */
     public function __construct()
     {
     	$this->middleware('auth');

     }

	/**
     * Redirect the user to the Eve Online authentication page.
     *
     * @return Response
     */

	public function routePlanning()
	{

	     // Fetch Your Characters from the database
		$characters = ScoutLocationShipOnline::where('slo_user_id', Auth::id())
		->sortable()->orderBY('slo_character_name')
		->get();

		$search = Input::input('search');

		if($search) {
			//check valid system

			$validSystem = SolarSystems::where('ss_system_name', $search)->first();

			if(!$validSystem) {

				return redirect()->back()
				->withErrors('This is not a valid system.');
			}

			// Calculate route for each Character & Update.
			$user = Auth::id();
			$tokens = ESITokens::where('esi_user_id', $user)
			->where('esi_active', 1)
			->get();

			$characters =  ScoutLocationShipOnline::where('slo_user_id', $user)->get();

			$destination = $validSystem->ss_system_id;

			foreach ($tokens as $character) {

				foreach($characters as $pilot) {

					if($character->esi_character_id == $pilot->slo_character_id) {

						$route = $this->getEveRoute($pilot->slo_solar_system_id, $destination, $character->esi_token);


						// $route requires path for all systems
						// Just couting jumps for now, not showing route.
						$jumps = count($route);

						$character = ScoutLocationShipOnline::updateOrCreate([
							'slo_character_id' 				 => $pilot->slo_character_id,
						],[
							'slo_desto_solar_system_id'					 => $destination,
							'slo_desto_solar_system_name'				 => $search,
							'slo_desto_solar_system_jumps'				 => $jumps,
						]);
					}
				}
			}

			return redirect()->back()
			->withSuccess('I Like Pie!');


		}

		return view('scout.location', compact('characters'));

	}

	public function scoutTracking()
	{

	     // Fetch Your Characters from the database
		$characters = ScoutLocationShipOnline::sortable()
		->orderBY('slo_character_name')
		->get();

		$search = Input::input('search');

		if($search) {
			//check valid system

			$validSystem = SolarSystems::where('ss_system_name', $search)->first();

			if(!$validSystem) {

				return redirect()->back()
				->withErrors('This is not a valid system.');
			}

			// Calculate route for each Character & Update.
			$tokens = ESITokens::where('esi_active', 1)
			->get();

			$characters =  ScoutLocationShipOnline::all();

			$destination = $validSystem->ss_system_id;

			foreach ($tokens as $character) {

				foreach($characters as $pilot) {

					if($character->esi_character_id == $pilot->slo_character_id) {

						$route = $this->getEveRoute($pilot->slo_solar_system_id, $destination, $character->esi_token);


						// $route requires path for all systems
						// Just couting jumps for now, not showing route.
						$jumps = count($route);

						$character_update = ScoutLocationShipOnline::updateOrCreate([
							'slo_character_id' 				 => $pilot->slo_character_id,
						],[
							'slo_desto_solar_system_id'					 => $destination,
							'slo_desto_solar_system_name'				 => $search,
							'slo_desto_solar_system_jumps'				 => $jumps,
						]);
					}
				}
			}

			return redirect()->back()
			->withSuccess('I Like Pie!');


		}

		return view('scout.tracking', compact('characters'));

	}


	public function updateMyCharactersLocation() 
	{

		// Get ESI Tokens for the Authed User

		$user = Auth::id();

		$tokens = ESITokens::where('esi_user_id', $user)
		->where('esi_active', 1)
		->get();

		foreach ($tokens as $character) {

			//dd($character);

	/*
	 * Endpoint: https://esi.evetech.net/ui/#/Location/get_characters_character_id_location
	 * function: getCharacterLocation()
	 * returns: solar_system_id, station_id, structure_id
	*/
	$location = $this->getCharacterLocation($character->esi_character_id, $character->esi_refresh_token);

	/*
	 * Endpoint: https://esi.evetech.net/ui/#/Location/get_characters_character_id_online
	 * function: getCharacterOnline()
	 * returns: last_login, last_logout, logins, online
	*/
	$online = $this->getCharacterOnline($character->esi_character_id, $character->esi_refresh_token);

	// Parse CCP Date Shit

	$last_login = $this->formatEveDate($online->last_login);
	$last_logout = $this->formatEveDate($online->last_logout);
	$logins = $online->logins;
	$online = $online->online;

	/* 
	 * Endpoint: https://esi.evetech.net/ui/#/Location/get_characters_character_id_ship
	 * function: getCharacterShip
	 * returns: ship_item_id, ship_name, ship_type_id
	*/

	$ship = $this->getCharacterShip($character->esi_character_id, $character->esi_refresh_token);

	$shipName = $ship->ship_name;
	$shipTypeName = $this->getTypeID($ship->ship_type_id);

	// Get Solar System from Database

	$system = $this->getSolarSystem($location->solar_system_id);

	// Update Each Character
	/*
		'slo_user_id',
     	'slo_character_id',
     	'slo_character_name',
     	'slo_solar_system_id',
     	'slo_solar_system_name',
     	'slo_region_id',
     	'slo_region_name',
     	'slo_station_id',
     	'slo_station_name',
     	'slo_structure_id',
     	'slo_structure_name',
     	'slo_last_login',
     	'slo_last_logout',
     	'slo_logins',
     	'slo_online',
     	'slo_ship_name',
     	'slo_ship_type_id',
     	'slo_ship_type_id_name',
     	'slo_desto_solar_system_id',
     	'slo_desto_solar_system_name',
     	'slo_desto_solar_system_jumps',
     */

     	$character = ScoutLocationShipOnline::updateOrCreate([
     		'slo_character_id' 				 => $character->esi_character_id,
     	],[
     		'slo_user_id'					 => $user,
     		'slo_character_name'			 => $character->esi_character_name,
     		'slo_corporation_id'			 => $character->esi_corporation_id,
     		'slo_corporation_name'			 => $character->esi_corporation_name,
     		'slo_solar_system_id'     		 => $location->solar_system_id,
     		'slo_solar_system_name'			 => $system->ss_system_name,
     		'slo_region_id'					 => $system->ss_region_id,
     		'slo_region_name'				 => $system->ss_region_name,
     		'slo_last_login'     	   		 => $last_login,
     		'slo_last_logout'     	   		 => $last_logout,
     		'slo_logins'     	   			 => $logins,
     		'slo_online'     	   			 => $online,
     		'slo_ship_name'					 => $shipName,
     		'slo_ship_type_id'				 => $ship->ship_type_id,
     		'slo_ship_type_id_name' 		 => $shipTypeName['name']
     	]);

     }


     return redirect()->back()
     ->withSuccess('I Like Pie!');

 }

 	public function updateAllCharactersLocations() 
	{

		// Get ESI Tokens for the Authed User

		$tokens = ESITokens::where('esi_active', 1)
		->get();

		foreach ($tokens as $character) {

			//dd($character);

	/*
	 * Endpoint: https://esi.evetech.net/ui/#/Location/get_characters_character_id_location
	 * function: getCharacterLocation()
	 * returns: solar_system_id, station_id, structure_id
	*/
	$location = $this->getCharacterLocation($character->esi_character_id, $character->esi_refresh_token);

	/*
	 * Endpoint: https://esi.evetech.net/ui/#/Location/get_characters_character_id_online
	 * function: getCharacterOnline()
	 * returns: last_login, last_logout, logins, online
	*/
	$online = $this->getCharacterOnline($character->esi_character_id, $character->esi_refresh_token);

	// Parse CCP Date Shit

	$last_login = $this->formatEveDate($online->last_login);
	$last_logout = $this->formatEveDate($online->last_logout);
	$logins = $online->logins;
	$online = $online->online;

	/* 
	 * Endpoint: https://esi.evetech.net/ui/#/Location/get_characters_character_id_ship
	 * function: getCharacterShip
	 * returns: ship_item_id, ship_name, ship_type_id
	*/

	$ship = $this->getCharacterShip($character->esi_character_id, $character->esi_refresh_token);

	$shipName = $ship->ship_name;
	$shipTypeName = $this->getTypeID($ship->ship_type_id);

	// Get Solar System from Database

	$system = $this->getSolarSystem($location->solar_system_id);

	// Update Each Character
	/*
		'slo_user_id',
     	'slo_character_id',
     	'slo_character_name',
     	'slo_solar_system_id',
     	'slo_solar_system_name',
     	'slo_region_id',
     	'slo_region_name',
     	'slo_station_id',
     	'slo_station_name',
     	'slo_structure_id',
     	'slo_structure_name',
     	'slo_last_login',
     	'slo_last_logout',
     	'slo_logins',
     	'slo_online',
     	'slo_ship_name',
     	'slo_ship_type_id',
     	'slo_ship_type_id_name',
     	'slo_desto_solar_system_id',
     	'slo_desto_solar_system_name',
     	'slo_desto_solar_system_jumps',
     */

     	$character = ScoutLocationShipOnline::updateOrCreate([
     		'slo_character_id' 				 => $character->esi_character_id,
     	],[
     		'slo_user_id'					 => $character->esi_user_id,
     		'slo_character_name'			 => $character->esi_character_name,
     		'slo_corporation_id'			 => $character->esi_corporation_id,
     		'slo_corporation_name'			 => $character->esi_corporation_name,
     		'slo_solar_system_id'     		 => $location->solar_system_id,
     		'slo_solar_system_name'			 => $system->ss_system_name,
     		'slo_region_id'					 => $system->ss_region_id,
     		'slo_region_name'				 => $system->ss_region_name,
     		'slo_last_login'     	   		 => $last_login,
     		'slo_last_logout'     	   		 => $last_logout,
     		'slo_logins'     	   			 => $logins,
     		'slo_online'     	   			 => $online,
     		'slo_ship_name'					 => $shipName,
     		'slo_ship_type_id'				 => $ship->ship_type_id,
     		'slo_ship_type_id_name' 		 => $shipTypeName['name']
     	]);

     }


     return redirect()->back()
     ->withSuccess('I Like Pie!');

 }


 public function mineAutocomplete(Request $request)
 {

 	$data = SolarSystems::
 	where("ss_system_name","LIKE","%{$request->input('query')}%")
 	->select('ss_system_name')
 	->get();

 	$systems = array();
 	foreach ($data as $system) {

 		$systems[] = $system->ss_system_name;
 	}

 	return response()->json($systems);
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
