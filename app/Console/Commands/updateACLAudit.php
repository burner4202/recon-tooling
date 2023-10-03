<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;

use Auth;
use Vanguard\ESITokens;
use Vanguard\Characters;
use Vanguard\AllianceStandings;
use Vanguard\Alliances;
use Vanguard\Corporations;
use Vanguard\ACLCharacters;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class updateACLAudit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'acl:audit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates ACLs with Character Corporation/Alliance/Standings';

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


    	$characters = ACLCharacters::groupBy('aclc_character_name')->where('aclc_member_type', 'character')->orderBy('updated_at', 'DESC')->get();

    	$bar = $this->output->createProgressBar(count($characters));
    	$bar->start();

    	# For each of the characters in the database, we need to update their corporation/alliance and standings.
    	foreach ($characters as $character) {

    		# Get Character Information from CCP.

    		if($character->aclc_character_id != 1) {
    			$response = $this->getCharacter($character->aclc_character_id);
    			$this->info($character->aclc_character_id);
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

 			## Update all Records were the Character exists

    			$all_characters = ACLCharacters::where('aclc_character_name', $response->name)->get();

    			foreach($all_characters as $update) {

    				$update->aclc_corporation_name = $corporation_name;
    				$update->aclc_corporation_id = $response->corporation_id;
    				$update->aclc_alliance_name = $alliance_name;
    				$update->aclc_alliance_id = $alliance_id;
    				$update->save();

    			}

    			$bar->advance();
    		}
    	}


    	$bar->finish();

    	$corporations = ACLCharacters::groupBy('aclc_character_name')->where('aclc_member_type', 'corporation')->where('aclc_character_id', '>', 3)->orderBy('updated_at', 'DESC')->get();

    	foreach($corporations as $corporation) {

    		# Update the Corporation
    		$corporation_cache = Corporations::where('corporation_name', $corporation->aclc_character_name)->first();

    		if($corporation_cache) {

    			$alliance_id = $corporation_cache->corporation_alliance_id;
    			$corporation_name = $corporation_cache->corporation_name;
    			$corporation_id =  $corporation_cache->corporation_corporation_id;

    		} else {

 				## Ask CCP for the Info.
    			$corporation_info = $this->getCorporation($corporation->aclc_character_id);
    			$corporation_name = $corporation_info->name;

 				# If corporation is part of an alliance, set id.

    			if(isset($corporation_info->alliance_id)) {
    				$alliance_id = $corporation_info->alliance_id;
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


    		$all_corporations = ACLCharacters::where('aclc_character_name', $corporation->aclc_character_name)->get();

    		foreach($all_corporations as $update) {

    			$update->aclc_alliance_name = $alliance_name;
    			$update->aclc_alliance_id = $alliance_id;
    			$update->save();

    		}



    	}

    }

    public function checkSuspect($character_id, $corporation_id, $alliance_id, $type) {

    	$standing = null;

    	if($type == 'character') {

            # Check if this Character is on the Standings List
    		$contact = AllianceStandings::where('as_contact_id', $character_id)->first();

            # Did we get a result?
    		if($contact) {
                # Set the Standing
    			$standing = $contact->as_standing;
    		} 

            # Check if the Character Corporation is on the Standing List
    		if($corporation_id > 2) {

                #Check Standings for the Corporation
    			$corporation_standing = AllianceStandings::where('as_corporation_id', $corporation_id)->first();

                # Did we find a result.
    			if($corporation_standing) {
                    # Set the standing
    				$standing = $corporation_standing->as_standing;
    			}

    		}

    		if($alliance_id > 2) {

                #Check Standings for the Corporation
    			$alliance_standing = AllianceStandings::where('as_alliance_id', $alliance_id)->first();

                # Did we find a result.
    			if($alliance_standing) {
                    # Set the standing
    				$standing = $corporation_standing->as_standing;
    			}

    		}
    	}
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
