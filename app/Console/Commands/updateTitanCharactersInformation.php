<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;

use Auth;
use Vanguard\ESITokens;
use Vanguard\Characters;
use Vanguard\AllianceStandings;
use Vanguard\Alliances;
use Vanguard\Corporations;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class updateTitanCharactersInformation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:updateTitanCharactersInformation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates Characters & Alliances/Corporations who have a Titan/Super/Carrier';

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


 		$characters = Characters::orderBy('updated_at', 'DESC')->get();
 		$bar = $this->output->createProgressBar(count($characters));
 		$bar->start();

 		foreach ($characters as $character) {

 			$response = $this->getCharacter($character->character_character_id);
 			$this->info($character->character_character_id);
 			# Check if we have the corporation cached.

 			$corporation_cache = Corporations::where('corporation_corporation_id', $response->corporation_id)->first();

 			if($corporation_cache) {

 				$alliance_id = $corporation_cache->corporation_alliance_id;
 				$corporation_name = $corporation_cache->corporation_name;

 			} else {

 				## Ask CCP for the Info.
 				$corporation = $this->getCorporation($response->corporation_id);
 				$corporation_name = $corporation->name;

 				 			# If corporation is part of an alliance, set id.

 				if(isset($corporation->alliance_id)) {
 					$alliance_id = $corporation->alliance_id;
 				} else {
 					$alliance_id = "";
 				}


 			}


 			# If the alliance id is more than 0, it exists.

 			if($alliance_id > 0) {

 				# Check if we have alliance cached.

 				$alliance_cache = Alliances::where('alliance_alliance_id', $alliance_id)->first();

 				if($alliance_cache) {

 					# It exists.

 					$alliance_name = $alliance_cache->alliance_name;

 				} else {

 					# Get Endpoint from CCP.

 					$alliance = $this->getAlliance($alliance_id);

 					$alliance_name = $alliance->name;

 				}

 				# Alliance not found. zero it out.

 			} else {

 				$alliance_name = "";
 				$alliance_id = "";
 			}

 			# Update the Character Database

 			$character = Characters::updateOrCreate([
 				'character_character_id' 		=> $character->character_character_id,
 			],[
 				'character_corporation_id' 		=> $response->corporation_id,
 				'character_corporation_name' 	=> $corporation_name,
 				'character_name' 				=> $response->name,
 				'character_security_status'   	=> $response->security_status,
 				'character_alliance_id'   		=> $alliance_id,
 				'character_alliance_name'   	=> $alliance_name,
 			]);



 			$bar->advance();
 		}


 		$bar->finish();

 	}



 	public function getCorporation($corporation_id)
 	{
 		$configuration = Configuration::getInstance();

 		$client_id = config('eve.client_id');
 		$secret_key = config('eve.secret_key');

 		try {

 			$esi = new Eseye();

 			$response = $esi->invoke('get', '/corporations/{corporation_id}/', [
 				'corporation_id' => $corporation_id,
 			]);

 			if(!isset($response->alliance_id)) { 
 				$corp = Corporations::updateOrCreate([
 					'corporation_corporation_id'      => $corporation_id,
 				],[
 					'corporation_ceo_id'            => $response->ceo_id,
 					'corporation_creator_id'        => $response->creator_id,
 					'corporation_member_count'      => $response->member_count,
 					'corporation_name'              => $response->name,
 					'corporation_tax_rate'          => $response->tax_rate,
 					'corporation_ticker'            => $response->ticker,
 				]);

				//$this->updateCharacter($response->creator_id);
				//$this->updateCharacter($response->ceo_id);


 			} else  {

 				$corp = Corporations::updateOrCreate([
 					'corporation_corporation_id'      => $corporation_id,
 				],[
 					'corporation_alliance_id'       => $response->alliance_id,
 					'corporation_ceo_id'            => $response->ceo_id,
 					'corporation_creator_id'        => $response->creator_id,
 					'corporation_date_founded'      => $response->date_founded,
 					'corporation_member_count'      => $response->member_count,
 					'corporation_name'              => $response->name,
 					'corporation_tax_rate'          => $response->tax_rate,
 					'corporation_ticker'            => $response->ticker,
 				]);

				//$this->updateCharacter($response->creator_id);
				//$this->updateCharacter($response->ceo_id);
 			}


 		}  catch (EsiScopeAccessDeniedException $e) {

 			$this->error('SSO Token is invalid');

 		} catch (RequestFailedException $e) {

 			$this->error('Got an ESI Error');

 		} catch (Exception $e) {

 			$this->error('ESI is fucked');
 		}


 		return $response;

 	}

 	public function getAlliance($alliance_id)
 	{
 		$configuration = Configuration::getInstance();

 		$client_id = config('eve.client_id');
 		$secret_key = config('eve.secret_key');

 		try {

 			$esi = new Eseye();

 			$response = $esi->invoke('get', '/alliances/{alliance_id}/', [
 				'alliance_id' => $alliance_id,
 			]);


 			$alliance = Alliances::updateOrCreate([
 				'alliance_alliance_id'     					=> $alliance_id,
 			],[
 				'alliance_creator_corporation_id'            => $response->creator_corporation_id,
 				'alliance_creator_id'       					=> $response->creator_id,
 				'alliance_date_founded'     					=> $response->date_founded,
 				'alliance_executor_corporation_id'      		=> $response->executor_corporation_id,
 				'alliance_name'              				=> $response->name,
 				'alliance_ticker'            				=> $response->ticker,
 			]);


			//$this->updateCharacter($response->creator_id);
 			//$this->updateCorporationsOfAlliance($alliance_id);
			//$this->updateCorporation($response->creator_corporation_id);
 			//$this->updateCorporation($response->executor_corporation_id);



 		}  catch (EsiScopeAccessDeniedException $e) {

 			$this->error('SSO Token is invalid');

 		} catch (RequestFailedException $e) {

 			$this->error('Got an ESI Error');

 		} catch (Exception $e) {

 			$this->error('ESI is fucked');
 		}

 		return $response;
 	}

 	public function getCharacter($character_id)
 	{

 		$configuration = Configuration::getInstance();

 		$client_id = config('eve.client_id');
 		$secret_key = config('eve.secret_key');

 		try {

 			$esi = new Eseye();

 			$response = $esi->invoke('get', '/characters/{character_id}/', [
 				'character_id' => $character_id,
 			]);

 		}  catch (EsiScopeAccessDeniedException $e) {

 			$this->error('SSO Token is invalid');

 		} catch (RequestFailedException $e) {

 			$this->error('Got an ESI Error ');
 			$this->info($character_id);

 		} catch (Exception $e) {

 			$this->error('ESI is fucked');
 		}

 		return $response;

 	}


 }
