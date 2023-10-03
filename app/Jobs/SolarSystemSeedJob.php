<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Datetime;

use Auth;
use Log;
use Carbon\Carbon;
use Vanguard\User;
use Vanguard\SolarSystems;
use Vanguard\TypeIDs;
use Vanguard\Jobs\BatchSolarSytemJob;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class SolarSystemSeedJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    // End Point: https://esi.evetech.net/ui/#/Universe/get_universe_systems_system_id
    public function handle() {


    	$configuration = Configuration::getInstance();

    	$client_id = config('eve.client_id');
    	$secret_key = config('eve.secret_key');

    	$esi = new Eseye();

    	try { 

			$systems = $esi->invoke('get', '/universe/systems', []);  // Query ESI for All Systems
			
			foreach ($systems as $system) { 			

				Queue::push((new SolarSystemSeedJob($system)));			  

			//$this->getSystemDetails($system);			  // Cycle Object and Send System ID to getSystemDetails function.

			}

		} catch (EsiScopeAccessDeniedException $e) {

			return redirect()->route('esi.index')
			->withErrors('Your ESI Token has been revoked, re-add it on the SSO page.');

		} catch (RequestFailedException $e) {

			return redirect()->route('esi.index')
			->withErrors('Got an ESI error ' . $e);

		} catch (Exception $e) {

			return redirect()->route('esi.index')
			->withErrors('CCPs ESI is fucked.');
		}
		

		//return redirect()->route('esi.index')
		//->withSuccess('Systems Seeded');
	}

	public function getSolarSystemetails($system) { // End Point: https://esi.evetech.net/ui/#/Universe/get_universe_systems

		$configuration = Configuration::getInstance();

		$client_id = config('eve.client_id');
		$secret_key = config('eve.secret_key');

		$esi = new Eseye();

		try { 

			$systemDetails = $esi->invoke('get', '/universe/systems/{system_id}/', [   
				'system_id' => $system,
			]);

			$updateSystem = SolarSystems::updateOrCreate([
				'system_id'      			=> $system,
			],[
				'name'  					=> $systemDetails->name,
				'security_status'  			=> $systemDetails->security_status,
				'constellation_id'  		=> $systemDetails->constellation_id,

			]);

		} catch (EsiScopeAccessDeniedException $e) {

			return redirect()->route('esi.index')
			->withErrors('Your ESI Token has been revoked, re-add it on the SSO page.');

		} catch (RequestFailedException $e) {

			return redirect()->route('esi.index')
			->withErrors('Got an ESI error ' . $e);

		} catch (Exception $e) {

			return redirect()->route('esi.index')
			->withErrors('CCPs ESI is fucked.');
		}

		//return redirect()->route('esi.index')
		//->withSuccess('Systems Seeded');

	}
}
