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
use Vanguard\User;
use Vanguard\ESITokens;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;



class ESIController extends Controller
{
     /**
     * ESIController constructor.
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

	public function index()
	{

	     // Fetch Your Characters from the database
		$characters = ESITokens::where('esi_user_id', Auth::user()->id)
		->sortable()
		/*
		->join('corporation', 'esi_tokens.esi_corporation_id', '=', 'corporation.corporation_corporation_id')
		->join('alliances', 'corporation.corporation_alliance_id', '=', 'alliances.alliance_alliance_id')
		->select(
			'esi_character_id',
			'esi_name',
			'esi_corporation_id',
			'esi_corporation_name',
			'corporation_ticker',
			'alliance_alliance_id',
			'alliance_name',
			'alliance_ticker',
			'esi_active',
			'esi_tokens.updated_at'
		)
		*/
		->orderBY('esi_name')
		->get();



		/*
		"id" => 1
        "esi_user_id" => 1
        "esi_name" => "scopehone"
        "esi_character_id" => 595698298
        "esi_avatar" => "https://image.eveonline.com/Character/595698298_128.jpg"
        "esi_token" => "**"
        "esi_refresh_token" => "**"
        "esi_scopes" => ""
        "esi_owner_hash" => "**"
        "esi_active" => 1
        "created_at" => "2019-05-20 16:02:43"
        "updated_at" => "2019-05-20 16:02:43"
        "esi_character_name" => "scopehone"
        "esi_corporation_id" => 230445889
        "esi_corporation_name" => "Mindstar Technology"
        "corporation_corporation_id" => 230445889
        "corporation_alliance_id" => 1354830081
        "corporation_ceo_id" => 354772370
        "corporation_creator_id" => 1778331558
        "corporation_date_founded" => "2005-04-07T21:40:00Z"
        "corporation_description" => ""
        "corporation_member_count" => 228
        "corporation_name" => "Mindstar Technology"
        "corporation_tax_rate" => "0.15"
        "corporation_ticker" => "MNDS"
        "corporation_url" => ""
        "alliance_alliance_id" => 1354830081
        "alliance_creator_corporation_id" => 459299583
        "alliance_creator_id" => 240070320
        "alliance_date_founded" => "2010-06-01T05:36:00Z"
        "alliance_executor_corporation_id" => 1344654522
        "alliance_name" => "Goonswarm Federation"
        "alliance_ticker" => "CONDI"
        */

        return view('esi.index', compact('characters'));

    }

    public function all() {

    	$characters = ESITokens::orderBY('esi_name', 'asc')
    	->sortable()
    	/*
    	->join('corporation', 'esi_tokens.esi_corporation_id', '=', 'corporation.corporation_corporation_id')
    	->join('alliances', 'corporation.corporation_alliance_id', '=', 'alliances.alliance_alliance_id')
    	->select(
    		'esi_character_id',
    		'esi_name',
    		'esi_corporation_id',
    		'esi_corporation_name',
    		'corporation_ticker',
    		'alliance_alliance_id',
    		'alliance_name',
    		'alliance_ticker',
    		'esi_active',
    		'esi_tokens.updated_at'
    	)
    	*/
    	->orderBY('esi_name')
    	->get();



    	return view('esi.all', compact('characters'));
    }

     /**
     * Redirect the user to the Eve Online authentication page.
     *
     * @return Response
     */
     
     public function redirectToProvider()
     {
     	return Socialite::driver('eveonline')
     	->scopes([
     		'publicData',
     		'esi-location.read_location.v1',
     		'esi-location.read_ship_type.v1',
     		'esi-location.read_online.v1',
     		'esi-fleets.read_fleet.v1',
     		'esi-fleets.write_fleet.v1',
     		'esi-ui.write_waypoint.v1 ',
     		'esi-alliances.read_contacts.v1',
     	])
     	->redirect();
     }

     public function redirectToProviderCorporation()
     {
     	return Socialite::driver('eveonline')
     	->scopes([
     		'publicData',
     		'esi-location.read_location.v1',
     		'esi-location.read_ship_type.v1',
     		'esi-location.read_online.v1',
     		'esi-fleets.read_fleet.v1',
     		'esi-fleets.write_fleet.v1',
     		'esi-ui.write_waypoint.v1 ',
     		'esi-alliances.read_contacts.v1',
     		'esi-contracts.read_corporation_contracts.v1',
     		'esi-assets.read_corporation_assets.v1',
     		'esi-characters.read_notifications.v1',

     	])
     	->redirect();
     }

     /**
     * Obtain the user information from Eve Online.
     *
     * @return Response
     */

     public function handleProviderCallback()
     {

     	$user = Socialite::driver('eveonline')->user();
     	$user_id = Auth::user()->id;
     	$active = 1;

     	$character = $this->getCharacter($user->id);
     	$corporation = $this->getCorporation($character->corporation_id);

     	$newESIToken = ESITokens::updateOrCreate([
     		'esi_character_id'                => $user->id,
     	],[
     		'esi_user_id'                     => $user_id,
     		'esi_name'                        => $user->name,
     		'esi_avatar'                      => $user->avatar,
     		'esi_token'                       => $user->token,
     		'esi_refresh_token'               => $user->refreshToken,
     		'esi_owner_hash'                  => $user->owner_hash,
     		'esi_active'                      => $active,
     		'esi_character_name'			  => $character->name,
     		'esi_corporation_id'			  => $character->corporation_id,
     		'esi_corporation_name'			  => $corporation->name
     	]);

     	return redirect()->route('esi.index');

     }

     public function getCharacter($character_id) {

     	$configuration = Configuration::getInstance();

     	$client_id = config('eve.client_id');
     	$secret_key = config('eve.secret_key');

     	$esi = new Eseye();

     	try { 

     		$character = $esi->invoke('get', '/characters/{character_id}/', [
     			'character_id' => $character_id,
     		]);


     	} catch (EsiScopeAccessDeniedException $e) {

     		return redirect()->route('esi.index')
     		->withErrors('Your ESI Token has been revoked, re-add it on the SSO page.');

     	} catch (RequestFailedException $e) {

     		return redirect()->route('esi.index')
     		->withErrors('Got an ESI error');

     	} catch (Exception $e) {

     		return redirect()->route('esi.index')
     		->withErrors('CCPs ESI is fucked.');
     	}

     	return $character;

     }

     public function getCorporation($corporation_id) {

     	$configuration = Configuration::getInstance();

     	$client_id = config('eve.client_id');
     	$secret_key = config('eve.secret_key');

     	$esi = new Eseye();

     	try { 

     		$response = $esi->invoke('get', '/corporations/{corporation_id}/', [
     			'corporation_id' => $corporation_id,
     		]);


     	} catch (EsiScopeAccessDeniedException $e) {

     		return redirect()->route('esi.index')
     		->withErrors('Your ESI Token has been revoked, re-add it on the SSO page.');

     	} catch (RequestFailedException $e) {

     		return redirect()->route('esi.index')
     		->withErrors('Got an ESI error');

     	} catch (Exception $e) {

     		return redirect()->route('esi.index')
     		->withErrors('CCPs ESI is fucked.');
     	}

     	return $response;

     }

 }
