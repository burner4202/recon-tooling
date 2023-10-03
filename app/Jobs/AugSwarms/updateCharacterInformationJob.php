<?php

namespace Vanguard\Jobs\AugSwarms;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

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
use Vanguard\AugswarmTracking;


use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class updateCharacterInformationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $character_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($character_id)
    {
        $this->character_id = $character_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $this->updateCharacter($this->character_id);
    }

    public function updateCharacter($character_id) 
    {
        # Get ESI Token

        $token = ESITokens::where('esi_character_id', $character_id)
        ->where('esi_active', 1)
        ->first();

        if($token) {

        $now = Carbon::now();

        $location      =     $this->getCharacterLocation($character_id, $token->esi_refresh_token);
        $online        =     $this->getCharacterOnline($character_id, $token->esi_refresh_token);
        $ship          =     $this->getCharacterShip($character_id, $token->esi_refresh_token);


        $last_login    =     $this->formatEveDate($online->last_login);
        $last_logout   =     $this->formatEveDate($online->last_logout);
        $logins        =     $online->logins;
        $online        =     $online->online;

        $shipName      =     $ship->ship_name;
        $shipTypeName  =     $this->getTypeID($ship->ship_type_id);

        $system        =     $this->getSolarSystem($location->solar_system_id);

        $character = AugswarmTracking::updateOrCreate([
            'at_character_id'               => $character_id,
        ],[
            'at_solar_system_id'            => $location->solar_system_id,
            'at_last_login'                 => $last_login,
            'at_last_logout'                => $last_logout,
            'at_logins'                     => $logins,
            'at_online'                     => $online,
            'at_ship_name'                  => $shipName,
            'at_ship_type_id'               => $ship->ship_type_id,
            'at_ship_type_id_name'          => $shipTypeName['name'],
            'at_last_updated'               => $now
        ]);
        }

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


    public function formatEveDate($date) 
    {
    $trimmed = rtrim($date, "Z");
    $dateAndTime = explode("T", $trimmed);
    $dt = Carbon::parse($dateAndTime[0] . " " . $dateAndTime[1]);   

    return $dt;   
    }



}
