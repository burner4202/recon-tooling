<?php

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


class SetWayPointController extends Controller
{

	public function setWayPoint($structure_id) {


    	// Get user id
		$user_id = Auth::id();

    	// Get register character

		$activeCharacter = ESITokens::where('esi_user_id', $user_id)->first();

    	// Check User has a Valid Token

		if(!$activeCharacter) {
			return redirect()->back()->withErrors('You do not have a valid token added.'); 
		}

    	// Check Character is Online.

		$character_online = $this->characterOnline($activeCharacter);

		if(!$character_online) {
			return redirect()->back()->withErrors('Your character is not online.'); 
		}

		// Set Way Point

		$configuration = Configuration::getInstance();
		$client_id = config('eve.client_id');
		$secret_key = config('eve.secret_key');
		$refresh_token = $activeCharacter->esi_refresh_token;

		$authentication = new EsiAuthentication([
			'client_id'     => $client_id,
			'secret'        => $secret_key,
			'refresh_token' => $refresh_token,
		]);

		$add_to_beginning = 0;
		$clear_other_waypoints = 0;

		try {
			$esi = new Eseye($authentication);

			$esi->setQueryString([
				'add_to_beginning' => $add_to_beginning,
				'clear_other_waypoints' => $clear_other_waypoints,
				'destination_id' => $structure_id,
			])->invoke('post', '/ui/autopilot/waypoint/');


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

		return redirect()->back()->withSuccess('Done! Check your client, All you gotta do is warp...');

	}

	public function setWayPointSystem($system_id) {


    	// Get user id
		$user_id = Auth::id();

    	// Get register character

		$activeCharacter = ESITokens::where('esi_user_id', $user_id)->first();

    	// Check User has a Valid Token

		if(!$activeCharacter) {
			return redirect()->back()->withErrors('You do not have a valid token added.'); 
		}

    	// Check Character is Online.

		$character_online = $this->characterOnline($activeCharacter);

		if(!$character_online) {
			return redirect()->back()->withErrors('Your character is not online.'); 
		}

		// Set Way Point

		$configuration = Configuration::getInstance();
		$client_id = config('eve.client_id');
		$secret_key = config('eve.secret_key');
		$refresh_token = $activeCharacter->esi_refresh_token;

		$authentication = new EsiAuthentication([
			'client_id'     => $client_id,
			'secret'        => $secret_key,
			'refresh_token' => $refresh_token,
		]);

		$add_to_beginning = 0;
		$clear_other_waypoints = 0;

		# Get the structures in the system with a structure id, no duplicates.

		$structures = KnownStructures::where('str_system_id', $system_id)
		->where('str_structure_id', '>', 1)
		->where('str_destroyed', 0)
		->groupBy('str_structure_id') 
		->orderBy('str_name', 'ASC')
		->get();

		foreach($structures as $structure) {

			try {
				$esi = new Eseye($authentication);

				$esi->setQueryString([
					'add_to_beginning' => $add_to_beginning,
					'clear_other_waypoints' => $clear_other_waypoints,
					'destination_id' => $structure->str_structure_id,
				])->invoke('post', '/ui/autopilot/waypoint/');


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

		}

		return redirect()->back()->withSuccess('Done! Check your client, All you gotta do is warp...');

	}

	public function openInformation($structure_id) {


    	// Get user id
		$user_id = Auth::id();

    	// Get register character

		$activeCharacter = ESITokens::where('esi_user_id', $user_id)->first();

    	// Check User has a Valid Token

		if(!$activeCharacter) {
			return redirect()->back()->withErrors('You do not have a valid token added.'); 
		}

    	// Check Character is Online.

		$character_online = $this->characterOnline($activeCharacter);

		if(!$character_online) {
			return redirect()->back()->withErrors('Your character is not online.'); 
		}

		// Set Way Point

		$configuration = Configuration::getInstance();
		$client_id = config('eve.client_id');
		$secret_key = config('eve.secret_key');
		$refresh_token = $activeCharacter->esi_refresh_token;

		$authentication = new EsiAuthentication([
			'client_id'     => $client_id,
			'secret'        => $secret_key,
			'refresh_token' => $refresh_token,
		]);

		$add_to_beginning = 0;
		$clear_other_waypoints = 0;

		try {
			$esi = new Eseye($authentication);

			$esi->setQueryString([
				'target_id' => $structure_id,
			])->invoke('post', '/ui/openwindow/information/');


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

		return redirect()->back()->withSuccess('Done! Check your client');

	}



	public function characterOnline($activeCharacter) {

		$configuration = Configuration::getInstance();


		$client_id = config('eve.client_id');
		$secret_key = config('eve.secret_key');
		$refresh_token = $activeCharacter->esi_refresh_token;

		$authentication = new EsiAuthentication([
			'client_id'     => $client_id,
			'secret'        => $secret_key,
			'refresh_token' => $refresh_token,
		]);

		$esi = new Eseye($authentication);

		try { 

			$character_online = $esi->invoke('get', '/characters/{character_id}/online/', [
				'character_id' => $activeCharacter->esi_character_id,
			]);

			if(!$character_online->online) { 

				return false;
			}

		} catch (EsiScopeAccessDeniedException $e) {

			return redirect()->back()->withErrors('Your ESI Token has been revoked, re-add it on the SSO page.');

		} catch (RequestFailedException $e) {

			return redirect()->back()->withErrors('Your ESI Token has been revoked, re-add it on the SSO page.');

		} catch (Exception $e) {

			return redirect()->back()->withErrors('ESI is down. ');
		}


		return true;

	}


	public function getLocation($activeCharacter) {

		$configuration = Configuration::getInstance();


		$client_id = config('eve.client_id');
		$secret_key = config('eve.secret_key');
		$refresh_token = $activeCharacter->esi_refresh_token;

		$authentication = new EsiAuthentication([
			'client_id'     => $client_id,
			'secret'        => $secret_key,
			'refresh_token' => $refresh_token,
		]);

		$esi = new Eseye($authentication);

		try { 

			$location = $esi->invoke('get', '/characters/{character_id}/location/', [
				'character_id' => $activeCharacter->esi_character_id,
			]);


		} catch (EsiScopeAccessDeniedException $e) {

			return redirect()->back()->withErrors('Your ESI Token has been revoked, re-add it on the SSO page.');

		} catch (RequestFailedException $e) {

			return redirect()->back()->withErrors('Your ESI Token has been revoked, re-add it on the SSO page.');

		} catch (Exception $e) {

			return redirect()->back()->withErrors('ESI is down. ');
		}


		return $location->solar_system_id;

	}
}
