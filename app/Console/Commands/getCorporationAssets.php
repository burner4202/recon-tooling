<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;

use Log;

use Carbon\Carbon;

use Vanguard\ESITokens;
use Vanguard\KnownStructures;
use Vanguard\ActivityTracker;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;


class getCorporationAssets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:getCorporationAssets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Corporation Assets for the Package Delivery Corporation';

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
    	$tokens = ESITokens::whereIn('esi_character_id', [94026378, 2112099979])
    	->where('esi_active', 1)
    	->get();

    	if($tokens) {

			foreach ($tokens as $token) {
			
			$this->info('Checking Corporation Assets For: ' . $token->esi_corporation_name);
    		$this->checkAssets($token);

			}
    	}

    }

	public function checkAssets($token) {

			$configuration = Configuration::getInstance();

    		$client_id = config('eve.client_id');
    		$secret_key = config('eve.secret_key');
    		$refresh_token = $token->esi_refresh_token;

    		$authentication = new EsiAuthentication([
    			'client_id'     => $client_id,
    			'secret'        => $secret_key,
    			'refresh_token' => $refresh_token,
    		]);

    		$esi = new Eseye($authentication);

    		try {

    			$response = $esi->invoke('get', '/corporations/{corporation_id}/assets/', [
                # Hard Coded, put into ENV
    				'corporation_id' => $token->esi_corporation_id,
    			]);


    		}  catch (EsiScopeAccessDeniedException $e) {

    			$this->error('ESI denied');

    		} catch (RequestFailedException $e) {

    			$this->error('ESI Failed');

    		} catch (Exception $e) {

    			$this->error('ESI fucked');
    		}

    		$pages = $response->headers['X-Pages'];

    		$this->info($pages . ' Pages found.');

    		$now = Carbon::now();

    		$count = 0;

    		for($current_page = 1; $current_page <= $pages; $current_page++) {

    			$response = $esi->page($current_page)->invoke('get', '/corporations/{corporation_id}/assets/', [
                # Hard Coded, put into ENV
    				'corporation_id' => $token->esi_corporation_id,
    			]);
                # Lets review every asset and see if we have a structure for it.
    			foreach($response as $line) {
                    //dd($line);                           

                 	# Lets get the structure ID.
    				$structures = KnownStructures::where('str_structure_id', $line->location_id)->get();

                    ## Lets check if it has a package, if it has we should validate it.
    				if($structures) {
    					foreach($structures as $structure) {
    						$count++;

    						$this->info($count . '. Structure ID: ' . $line->location_id . ' has an asset, structure name, ' . $structure->str_name . ' Date: ' . $now->format('Y-m-d'));
                        //$this->info('Structure Name: ' . $structure->str_name);
    					$structure->timestamps = false;
                        $structure->str_vertified_package = $now->format('Y-m-d');
                        $structure->str_package_delivered = "Package Vertified";
                        $structure->save();

                        

                        //$action = "Package Removed";
                        //$this->addActivityLogToStructure($structure, $action);
    					}
    				}




    			}
    		}


        //$this->info($assets);
	}
    public function addActivityLogToStructure($structure, $user_action) {

    	$user = "System";

    	$action = new ActivityTracker;
    	$action->at_user_id = 0;
    	$action->at_username = $user;
    	$action->at_structure_id = $structure->str_structure_id;
    	$action->at_structure_hash = $structure->str_structure_id_md5;
    	$action->at_structure_name = $structure->str_name;
    	$action->at_system_id = $structure->str_system_id;
    	$action->at_system_name = $structure->str_system;
    	$action->at_corporation_id = $structure->str_owner_corporation_id;
    	$action->at_corporation_name = $structure->str_owner_corporation_name;
    	$action->at_action = $user_action;
    	$action->save();
    }
}
