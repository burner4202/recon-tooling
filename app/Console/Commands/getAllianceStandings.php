<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;

use Auth;
use Vanguard\ESITokens;
use Vanguard\Characters;
use Vanguard\AllianceStandings;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;


class getAllianceStandings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:getAllianceStandings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets Alliance Standings and stores to Database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
    	parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

    	$token = ESITokens::where('esi_character_name', 'scopehone')->first();

    	$configuration = Configuration::getInstance();
    	
    	$client_id = config('eve.client_id');
    	$secret_key = config('eve.secret_key');
    	$refresh_token = $token->esi_refresh_token;

    	$authentication = new EsiAuthentication([
    		'client_id'     => $client_id,
    		'secret'        => $secret_key,
    		'refresh_token' => $refresh_token,
    	]);

    	AllianceStandings::truncate();

    	$esi = new Eseye($authentication);

    	try {

    		

    		$response = $esi->invoke('get', '/alliances/{alliance_id}/contacts/', [
    			'alliance_id' => 1354830081,
    		]);

    		$bar = $this->output->createProgressBar(count($response));
    		$bar->start();



    		
    		foreach ($response as $contact) {   

    			if($contact->contact_type == "character") {

    				$character = $esi->invoke('get', '/characters/{character_id}/', [
    					'character_id' => $contact->contact_id,
    				]);

    				$corporation = $esi->invoke('get', '/corporations/{corporation_id}/', [
    					'corporation_id' => $character->corporation_id,
    				]);

    				if(isset($corporation->alliance_id)) {

    					$alliance = $esi->invoke('get', '/alliances/{alliance_id}/', [
    						'alliance_id' => $corporation->alliance_id,
    					]);

    					$alliance_id = $corporation->alliance_id;
    					$alliance_name = $alliance->name;
    				} else {

    					$alliance_id = "";
    					$alliance_name = "";
    				}

    				$update = AllianceStandings::updateOrCreate([
    					'as_contact_id' 		=> $contact->contact_id,
    				],[
    					'as_contact_type' 	=> $contact->contact_type,
    					'as_standing' 	=> $contact->standing,
    					'as_character_name' => $character->name,
    					'as_corporation_id' => $character->corporation_id,
    					'as_corporation_name' =>$corporation->name,
    					'as_alliance_id' => $alliance_id,
    					'as_alliance_name' => $alliance_name,

    				]);

    				// End If
    			}


    			if($contact->contact_type == "corporation") {

    				$corporation = $esi->invoke('get', '/corporations/{corporation_id}/', [
    					'corporation_id' => $contact->contact_id,
    				]);

    				if(isset($corporation->alliance_id)) {

    					$alliance = $esi->invoke('get', '/alliances/{alliance_id}/', [
    						'alliance_id' => $corporation->alliance_id,
    					]);

    					$alliance_id = $corporation->alliance_id;
    					$alliance_name = $alliance->name;
    				} else {

    					$alliance_id = "";
    					$alliance_name = "";
    				}

    				$update = AllianceStandings::updateOrCreate([
    					'as_contact_id' 		=> $contact->contact_id,
    				],[
    					'as_contact_type' 	=> $contact->contact_type,
    					'as_standing' 	=> $contact->standing,
    					'as_corporation_id' => $contact->contact_id,
    					'as_corporation_name' =>$corporation->name,
    					'as_alliance_id' => $alliance_id,
    					'as_alliance_name' => $alliance_name,

    				]);

    				// End If
    			}

    			if($contact->contact_type == "alliance") {

    				$alliance = $esi->invoke('get', '/alliances/{alliance_id}/', [
    					'alliance_id' => $contact->contact_id,
    				]);

    				$alliance_id = $contact->contact_id;
    				$alliance_name = $alliance->name;


    				$update = AllianceStandings::updateOrCreate([
    					'as_contact_id' 		=> $contact->contact_id,
    				],[
    					'as_contact_type' 	=> $contact->contact_type,
    					'as_standing' 	=> $contact->standing,
    					'as_alliance_id' => $alliance_id,
    					'as_alliance_name' => $alliance_name,

    				]);

    				// End If
    			}

    			$bar->advance();


    			// End Foreach


    			# Add Goonswarm to Alliances

    			$update = AllianceStandings::updateOrCreate([
    				'as_contact_id' 		=> 1354830081,
    			],[
    				'as_contact_type' 		=> 'alliance',
    				'as_standing' 			=> 10.00,
    				'as_alliance_id' 		=> 1354830081,
    				'as_alliance_name' 		=> 'Goonswarm Federation',

    			]);
    		}

    		$bar->finish();


    	}  catch (EsiScopeAccessDeniedException $e) {

    		$this->error('SSO Token is invalid');

    	} catch (RequestFailedException $e) {

    		$this->error('Got an ESI Error');

    	} catch (Exception $e) {

    		$this->error('ESI is fucked');
    	}

    }
}

