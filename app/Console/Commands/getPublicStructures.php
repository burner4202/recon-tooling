<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;

use Datetime;

use Auth;
use Log;
use Carbon\Carbon;
use Vanguard\User;
use Vanguard\SolarSystems;
use Vanguard\Regions;
use Vanguard\Constellations;
use Vanguard\TypeIDs;
use Vanguard\Jobs\BatchSolarSytemJob;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;


class getPublicStructures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:getPublicStructures';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets Public Structures & Owner';

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


    	$configuration = Configuration::getInstance();
    	$client_id = config('eve.client_id');
    	$secret_key = config('eve.secret_key');

    	$esi = new Eseye();

    	try { 

    		$structures = $esi->invoke('get', '/universe/structures/', []);



    		foreach ($structures as $structure) {

    			$this->info($structure);

    			//$this->getStructure($structure);
    		}




    	} catch (EsiScopeAccessDeniedException $e) {

    		$this->error('SSO Token is invalid');

    	} catch (RequestFailedException $e) {

    		$this->error('Got an ESI Error');

    	} catch (Exception $e) {

    		$this->error('ESI is fucked');
    	}


    }

    public function getStructure($structure_id) {

    	$configuration = Configuration::getInstance();
    	$client_id = config('eve.client_id');
    	$secret_key = config('eve.secret_key');

    	$esi = new Eseye();

    	try { 

    		$structure = $esi->invoke('get', '/universe/structures/{structure_id}', [   
    			'structure_id' => $structure_id,
    		]);


    		$owner = $this->getStructureOwner($structure->owner_id);
    		$solar_system = $this->getSolarSystem($structure->system_id);
    		//$structure_type = $this->getStructureType($type_id);

    		$this->info('Structure Name: ' . $structure->name . ' Owner: ' . $owner . ' Solar System: ' . $solar_system . ' Type: ' . $structure->type_id);


    	} catch (EsiScopeAccessDeniedException $e) {

    		$this->error('SSO Token is invalid');

    	} catch (RequestFailedException $e) {

    		$this->error('Got an ESI Error' . $e);

    	} catch (Exception $e) {

    		$this->error('ESI is fucked');
    	}

    }

    /**
     * Display the ledger for the character
     *
     * @return Response
    */
    public function getSolarSystem($system_id)
    {

        // Get Ledger for User

    	$system = SolarSystems::where('system_id', $system_id)
    	->first();

    	return $system['name'];

    }

    public function getStructureOwner($corporation_id) {

    	$configuration = Configuration::getInstance();
    	$client_id = config('eve.client_id');
    	$secret_key = config('eve.secret_key');

    	$esi = new Eseye();

    	try { 

    		$response = $esi->invoke('get', '/corporations/{corporation_id}/', [   
    			'corporation_id' => $corporation_id,
    		]);

    		return $response->name;


    	} catch (EsiScopeAccessDeniedException $e) {

    		$this->error('SSO Token is invalid');

    	} catch (RequestFailedException $e) {

    		$this->error('Got an ESI Error');

    	} catch (Exception $e) {

    		$this->error('ESI is fucked');
    	}

    }






}
