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

class seedSolarSystems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:seedsolarsystems';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates all eve Solar Systems into Mining Database';

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
    public function handle()     {
         // End Point: https://esi.evetech.net/ui/#/Universe/get_universe_systems_system_id

    	$configuration = Configuration::getInstance();

    	$client_id = config('eve.client_id');
    	$secret_key = config('eve.secret_key');

    	$esi = new Eseye();

    	try { 

			//$systems = $esi->invoke('get', '/universe/systems', []);  // Query ESI for All Systems

			$regions = $esi->invoke('get', '/universe/regions/', []);
			
			foreach ($regions as $region) { 			

			$this->getRegionDetails($region);			  // Cycle Object and Send System ID to getSystemDetails function.

			}

		} catch (EsiScopeAccessDeniedException $e) {

			$this->error('SSO Token is invalid');

		} catch (RequestFailedException $e) {

			$this->error('Got an ESI Error');

		} catch (Exception $e) {

			$this->error('ESI is fucked');
		}
		

		//$this->info('Complete');
	}



    	public function getRegionDetails($region) { // End Point: https://esi.evetech.net/ui/#/Universe/get_universe_systems

    	$configuration = Configuration::getInstance();

    	$client_id = config('eve.client_id');
    	$secret_key = config('eve.secret_key');

    	$esi = new Eseye();

    	try { 


    		// Get the Region Details

    		$regionDetails = $esi->invoke('get', '/universe/regions/{region_id}/', [   
    			'region_id' => $region,
    		]);

    		if(!isset($regionDetails->description)) { $description = ""; } else { $description = $regionDetails->description; }

    		$updateRegion = Regions::updateOrCreate([
    			'reg_region_id'      			=> $region,
    		],[
    			'reg_region_name'  				=> $regionDetails->name,
    			'reg_description'  				=> $description,
    			'reg_constellations'			=> json_encode($regionDetails->constellations)
    		]);

    		// Update Constellations

    		foreach ($regionDetails->constellations as $constellation) {

	    	$this->getConstellationDetails($constellation);

    		}

    		$this->info('Region Added: ' . $regionDetails->name);

 		} catch (EsiScopeAccessDeniedException $e) {

			$this->error('SSO Token is invalid');

		} catch (RequestFailedException $e) {

			$this->error('Got an ESI Error');

		} catch (Exception $e) {

			$this->error('ESI is fucked');
		}
		

		//$this->info('Complete');

    }

        public function getConstellationDetails($constellation) { // End Point: https://esi.evetech.net/ui/#/Universe/get_universe_systems

    	$configuration = Configuration::getInstance();

    	$client_id = config('eve.client_id');
    	$secret_key = config('eve.secret_key');

    	$esi = new Eseye();

    	try { 

    		$constellationDetails = $esi->invoke('get', '/universe/constellations/{constellation_id}/', [   
    			'constellation_id' => $constellation,
    		]);

    		$region_name = $this->getRegionName($constellationDetails->region_id);

    		$updateConstellation = Constellations::updateOrCreate([
    			'con_constellation_id'      	=> $constellation,
    		],[
    			'con_constellation_name'  		=> $constellationDetails->name,
    			'con_region_id'  				=> $constellationDetails->region_id,
    			'con_region_name'				=> $region_name,
    			'con_position'					=> json_encode($constellationDetails->position),
    			'con_systems'					=> json_encode($constellationDetails->systems),
    		]);

    		$this->info('Constellation Added: ' . $constellationDetails->name);

    		    		// Update Systems

    		foreach ($constellationDetails->systems as $system) {

	    	$this->getSystemDetails($system);

    		}

 		} catch (EsiScopeAccessDeniedException $e) {

			$this->error('SSO Token is invalid');

		} catch (RequestFailedException $e) {

			$this->error('Got an ESI Error');

		} catch (Exception $e) {

			$this->error('ESI is fucked');
		}
		

		//$this->info('Complete');

    }






    public function getSystemDetails($system) { // End Point: https://esi.evetech.net/ui/#/Universe/get_universe_systems

    	$configuration = Configuration::getInstance();

    	$client_id = config('eve.client_id');
    	$secret_key = config('eve.secret_key');

    	$esi = new Eseye();

    	try { 

    		$systemDetails = $esi->invoke('get', '/universe/systems/{system_id}/', [   
    			'system_id' => $system,
    		]);

    		if(!isset($systemDetails->security_class)) { $security_class = ""; } else { $security_class = $systemDetails->security_class; }
    		if(!isset($systemDetails->stargates)) { $stargates = json_encode(""); } else { $stargates = json_encode($systemDetails->stargates); }

    		$constellation = $this->getConstellationName($systemDetails->constellation_id);

    		$updateSystem = SolarSystems::updateOrCreate([
    			'ss_system_id'      			=> $system,
    		],[
    			'ss_system_name'  				=> $systemDetails->name,
    			'ss_security_class'  			=> $security_class,
    			'ss_security_status'  			=> $systemDetails->security_status,
    			'ss_constellation_id'  		    => $systemDetails->constellation_id,
    			'ss_constellation_name'  		=> $constellation->con_constellation_name,
    			'ss_region_id'  				=> $constellation->con_region_id,
    			'ss_region_name'  				=> $constellation->con_region_name,
    			'ss_position'					=> json_encode($systemDetails->position),
    			'ss_stargates'					=> $stargates,
    		]);

    		$this->info('System Added: ' . $systemDetails->name);

 		} catch (EsiScopeAccessDeniedException $e) {

			$this->error('SSO Token is invalid');

		} catch (RequestFailedException $e) {

			$this->error('Got an ESI Error');

		} catch (Exception $e) {

			$this->error('ESI is fucked');
		}
		

		//$this->info('Complete');

    }


    public function getRegionName($region_id) {

    	$region = Regions::where('reg_region_id', $region_id)
    	->first();

    	return $region->reg_region_name;
    }

        public function getConstellationName($constellation_id) {

    	$constellation = Constellations::where('con_constellation_id', $constellation_id)
    	->first();

    	return $constellation;
    }



}