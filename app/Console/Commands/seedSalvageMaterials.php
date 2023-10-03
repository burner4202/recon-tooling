<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;

use Auth;
use Log;
use Carbon\Carbon;
use Vanguard\User;
use Vanguard\Salvage;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class seedSalvageMaterials extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:seedSalvageMaterials';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seeds Salvage to Database';

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

      	$typeIDs = [

    	// https://everef.net/market/501
    	// https://esi.evetech.net/ui/#/Industry

        // Salvage
        '25595',
        '25605',
        '25616',
        '25596',
        '25600',
        '25622',
        '25599',
        '25604',
        '25623',
        '25591',
        '25590',
        '25611',
        '25597',
        '25592',
        '25615',
        '25625',
        '25601',
        '25621',
        '25624',
        '25608',
        '25620',
        '25619',
        '25610',
        '25589',
        '25603',
        '25618',
        '25609',
        '25617',
        '25613',
        '25588',
        '25614',
        '25593',
        '25594',
        '25607',
        '25602',
        '25612',
        '25598',
        '25606',     
        '11483',
        '11475',
        '11481',
        '11486',
        '11476',
        '11482', 
    ];


    foreach ($typeIDs as $salvage) {

    		$this->getTypeID($salvage);			  // Cycle Array and Send TypeID Materials to save into DB.

    	}


    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
	public function getTypeID($salvage) { // End Point: https://esi.evetech.net/ui/#/Universe/get_universe_types_type_id

		$configuration = Configuration::getInstance();

		$client_id = config('eve.client_id');
		$secret_key = config('eve.secret_key');

		$esi = new Eseye();

		try { 

			$response = $esi->invoke('get', '/universe/types/{type_id}/', [   
				'type_id' => $salvage,
			]);

			$updatesalvage = Salvage::updateOrCreate([
				'type_id'      				=> $salvage,
			],[
				'name'  					=> $response->name,
				'description'  				=> $response->description,
				'group_id'  				=> $response->group_id,
				'icon_id'  					=> $response->icon_id,
				'volume'  					=> $response->volume,
			]);

			$this->info('Salvage Added: ' . $response->name);

		} catch (EsiScopeAccessDeniedException $e) {

			$this->error('SSO Token is invalid');

		} catch (RequestFailedException $e) {

			$this->error('Got an ESI Error');

		} catch (Exception $e) {

			$this->error('ESI is fucked');
		}


		//$this->info('Complete');
	}
}
