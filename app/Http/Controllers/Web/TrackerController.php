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

use Auth;
use Log;
use DB;
use Input;
use Carbon\Carbon;
use Vanguard\User;
use Vanguard\SolarSystems;
use Vanguard\Regions;
use Vanguard\Constellations;
use Vanguard\TypeIDs;
use Vanguard\NPCKills;
use Vanguard\KnownStructures;
use Vanguard\Alliances;
use Vanguard\ESITokens;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class TrackerController extends Controller
{
	public function trackMe() {

		$character_name = Input::input('character_name');

		if($character_name) {

    		// Get user id
			$user_id = Auth::id();

    		// Get register character
			$activeCharacter = ESITokens::where('esi_name', $character_name)
			->first();

			// Does it Belong
			if(!$user_id == $activeCharacter->esi_user_id) {
				return redirect()->back()->withErrors('This character does not belong to you, stop being a wanker.'); 
			}

    		// Check User has a Valid Token

			if(!$activeCharacter) {
				return redirect()->back()->withErrors('You do not have a valid token added.'); 
			}

    		// Check Character is Online.
			/* Removed, because i don't really care if its online or not.
			$character_online = $this->characterOnline($activeCharacter);

			if(!$character_online) {
				return redirect()->back()->withErrors('Your character is not online.'); 
			}
			*/

			// Find system and redirect.

			$location = $this->getLocation($activeCharacter);

			return redirect()->route('solar.system', $location);

		} else {

			return redirect()->back()->withErrors('Please select a character.'); 
		}

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
