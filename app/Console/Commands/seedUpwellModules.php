<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;

use Datetime;

use Auth;
use Log;
use Carbon\Carbon;
use Vanguard\User;
use Vanguard\SolarSystems;
use Vanguard\UpwellModules;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class seedUpwellModules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:seedUpwellModules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed EVE Upwell Modules into the Database';

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
    public function handle() {

    	$modules = [
    		46577,
    		35940,
    		47338,
    		35941,
    		47368,
    		35943,
    		47351,
    		47069,
    		35944,
    		47070,
    		47071,
    		47072,
    		47073,
    		47074,
    		35947,
    		47366,
    		35949,
    		47334,
    		35945,
    		47364,
    		35963,
    		47344,
    		37532,
    		47348,
    		47360,
    		47362,
    		47352,
    		47353,
    		47356,
    		47358,
    		46575,
    		35925,
    		47332,
    		35924,
    		47330,
    		35926,
    		47327,
    		35965,
    		47347,
    		35894,
    		35892,
    		35881,
    		45550,
    		35886,
    		35878,
    		35891,
    		35877,
    		45539,
    		45537,
    		45538,
    		45009,
    		35899,
    		35928,
    		35923,
    		47325,
    		35921,
    		47323,
    		35922,
    		47298,
    		35921,
    		47323,
    		35922,
    		47298,
    		35959,
    		47342,
    		35962,
    		47340,    

    		// Rigs

    		// Large Combat

    		37256,
    		37257,
    		37250,
    		37251,
    		37254,
    		37255,
    		37248,
    		37249,
    		37258,
    		37259,
    		37260,
    		37261,

    		// Medium Combat

    		37230,
    		37231,
    		37228,
    		37229,
    		37222,
    		37223,
    		37218,
    		37219,
    		37234,
    		37235,
    		37216,
    		37217,
    		37220,
    		37221,
    		37232,
    		37233,

    		// X Large Combat

    		37272,
    		37273,
    		37274,
    		37275,
    		37268,
    		37269,

    		// Engineering Large

    		37174,
    		37175,
    		37168,
    		37169,
    		43709,
    		43711,
    		43707,
    		43708,
    		37164,
    		37165,
    		43718,
    		43719,
    		37166,
    		37167,
    		43716,
    		43717,
    		43714,
    		43715,
    		43729,
    		43730,
    		37173,
    		37172,
    		43712,
    		43713,
    		37170,
    		37171,
    		43722,
    		43723,
    		43724,
    		43725,
    		43720,
    		43721,
    		43726,
    		43727,
    		45641,
    		45546,

    		// Engineering Medium

    		43867,
    		43866,
    		43869,
    		43868,
    		43862,
    		43863,
    		43865,
    		43864,
    		43858,
    		43859,
    		43860,
    		43861,
    		43855,
    		43854,
    		43856,
    		43857,
    		37158,
    		37159,
    		37150,
    		37151,
    		43870,
    		43871,
    		43872,
    		43873,
    		43732,
    		37152,
    		43733,
    		43734,
    		37146,
    		37147,
    		43919,
    		37153,
    		37154,
    		37155,
    		37162,
    		37163,
    		43893,
    		43892,
    		43891,
    		43890,
    		37156,
    		37157,
    		37148,
    		37149,
    		43920,
    		43921,
    		37160,
    		37161,
    		43880,
    		43881,
    		43879,
    		43878,
    		43883,
    		43882,
    		43885,
    		43884,
    		43875,
    		43874,
    		43876,
    		43877,
    		43889,
    		43888,
    		43887,
    		43886,
    		45640,
    		45544,

    		// Engineering XL

    		37178,
    		37179,
    		37183,
    		37182,
    		37180,
    		37181,
    		43704,
    		43705,
    		45548,

    		// Structure Processing
    		// LArge

    		//41428,
    		//41429,
    		//37282,
    		//37283,
    		46327,
    		46328,
    		46496,
    		46497,
    		46639,
    		46640,

    		// Medium

    		//37280,
    		//37281,
    		//41424,
    		//41425,
    		//41426,
    		//41427,
    		//41422,
    		//41423,
    		46325,
    		46633,
    		46634,
    		46494,
    		46495,
    		46492,
    		46493,
    		46486,
    		46487,
    		46484,
    		46485,
    		46490,
    		46491,
    		46488,
    		46489,
    		46635,
    		46636,
    		46323,
    		46324,
    		46326,
    		46637,
    		46638,

    		// XL

    		//37284,
    		//37285,
    		46641,
    		46642,

    		// Festival Lanucher

    		47303,

    		// Ammo

    		// Anti Cap Missles

    		//37845,
    		//37843,
    		//37844,

    		// Anti Sub Cap Missles

    		//37846,
    		//37847,
    		//37848,

    		// ECM

    		//37821,
    		//37822,
    		//37823,
    		//37824,

    		// Festival

    		//47300,
    		//47301,

    		// Bombs

    		//37849,
    		//37850,
    		//37851,

    		// Scripts Switcher ??

    		//37827,
    		//37826,
    		//37828,
    		//37829,

    		// Distrupter Script

    		47336,




    	];

    	foreach ($modules as $typeID) {

    	// Cycle Type IDs and Save them

    		$this->getTypeIDs($typeID);
    	}


    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
	public function getTypeIDs($typeID) { // End Point: https://esi.evetech.net/ui/#/Universe/get_universe_types_type_id

		$configuration = Configuration::getInstance();

		$client_id = config('eve.client_id');
		$secret_key = config('eve.secret_key');

		$esi = new Eseye();

		try { 

			$response = $esi->invoke('get', '/universe/types/{type_id}/', [   
				'type_id' => $typeID,
			]);

			$slot = "";

			foreach ($response->dogma_effects as $module_slot) {

				if($module_slot->effect_id == 11) {
					// Low Power Slot Needed
					$slot = "Low";
				}

				if($module_slot->effect_id == 12) {
					// High Power Slot Needed
					$slot = "High";
				}

				if($module_slot->effect_id == 13) {
					// Medium Power Slot Needed
					$slot = "Medium";
				}

				if($module_slot->effect_id == 6306) {
					// Service Slot Needed
					$slot = "Service Slot";
				}

				if($module_slot->effect_id == 2663) {
					// Rig Slot Needed
					$slot = "Rig";
				}

			}


			$update = UpwellModules::updateOrCreate([
				'upm_type_id'      				=> $typeID,
			],[
				'upm_name'  					=> $response->name,
				'upm_description'  				=> $response->description,
				'upm_dogma_attributes'  		=> json_encode($response->dogma_attributes),
				'upm_dogma_effects'  			=> json_encode($response->dogma_effects),
				'group_id'  					=> $response->group_id,
				'market_group_id'  				=> $response->market_group_id,
				'slot'							=> $slot,
			]);

			$this->info('Added: ' . $response->name);

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


