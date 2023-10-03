<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;

use Datetime;

use Auth;
use Log;
use DB;
use Input;
use Carbon\Carbon;
use Vanguard\User;
use Vanguard\SolarSystems;
use Vanguard\Regions;
use Vanguard\Constellations;
use Vanguard\TypeIDs;
use Vanguard\NPCKills;
use Vanguard\KnownStructures;
use Vanguard\Corporations;
use Vanguard\Alliances;
use Vanguard\UpwellModules;
use Vanguard\ActivityTracker;
use Vanguard\MarketPrices;
use Vanguard\UpwellRigs;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class seedPreviousStructurePricesHack extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:seedPreviousStructurePricesHack';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Previous Structure Prices & Fittings';

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

    	$structures = KnownStructures::where('str_value', '>', 0)->get();

    	$bar = $this->output->createProgressBar(count($structures));
    	$bar->start();

    	foreach ($structures as $structure) {

    		// Get Structure

    		$fittingArray = array();
    		$fittingValue = 0;

    		$cloning = 0;
    		$market = 0;
    		$capital_shipyard = 0;
    		$hyasyoda = 0;
    		$invention = 0;
    		$manufacturing = 0;
    		$research = 0;
    		$supercapital_shipyard = 0;
    		$biochemical = 0;
    		$composite = 0;
    		$hybrid = 0;
    		$moon_drilling = 0;
    		$reprocessing = 0;
    		$point_defense = 0;
    		$dooms_day = 0;
    		$guide_bombs = 0;
    		$anti_cap = 0;
    		$anti_subcap = 0;
    		$t2_rigged = 0;

    		$bar->advance();

    		foreach (json_decode($structure->str_fitting) as $fitted_module) {

    			// Get Fitting
				// We set up module mapping before adding it to the struture database
				// Map Module

    			if($fitted_module->name == "Standup Cloning Center I") {	$cloning = 1; }
    			if($fitted_module->name == "Standup Market Hub I") {	$market = 1; }
    			if($fitted_module->name == "Standup Capital Shipyard I") { $capital_shipyard = 1; }
    			if($fitted_module->name == "Standup Hyasyoda Research Lab") { $hyasyoda = 1; }
    			if($fitted_module->name == "Standup Invention Lab I") { $invention = 1; }
    			if($fitted_module->name == "Standup Manufacturing Plant I") { $manufacturing = 1; }
    			if($fitted_module->name == "Standup Research Lab I") { $research = 1; }
    			if($fitted_module->name == "Standup Supercapital Shipyard I") { $supercapital_shipyard = 1; }
    			if($fitted_module->name == "Standup Biochemical Reactor I") { $biochemical = 1; }
    			if($fitted_module->name == "Standup Composite Reactor I") { $composite = 1; }
    			if($fitted_module->name == "Standup Hybrid Reactor I") {	$hybrid = 1; }
    			if($fitted_module->name == "Standup Moon Drill I") {	$moon_drilling = 1; }
    			if($fitted_module->name == "Standup Reprocessing Facility I") {	$reprocessing = 1; }
    			if($fitted_module->name == "Standup Point Defense Battery I" || $fitted_module->name == "Standup Point Defense Battery II") {	$point_defense = 1; }
    			if($fitted_module->name == "Standup Arcing Vorton Projector I") { $dooms_day = 1; }
    			if($fitted_module->name == "Standup Guided Bomb Launcher I" || $fitted_module->name == "Standup Guided Bomb Launcher II") { $guide_bombs = 1; }
    			if($fitted_module->name == "Standup Anticapital Missile Launcher I" || $fitted_module->name == "Standup Anticapital Missile Launcher II") { $anti_cap = 1; }
    			if($fitted_module->name == "Standup Multirole Missile Launcher I" || $fitted_module->name == "Standup Multirole Missile Launcher II") { $anti_subcap = 1; }
    			if($fitted_module->name == "Standup Market Hub I") {	$market = 1; }


				// use Regex Preg Match to find a t2 rig.

    			if (preg_match('/(?=.*-Set)(?=.*II)/', $fitted_module->name)) {
    				$t2_rigged = 1;
    			}

				// Does this module exist in our own database?

    			$exists = UpwellModules::where('upm_name', $fitted_module->name)->first();

    			if ($exists) {

				// If the module does exist we get the price of the module from our own database that we update every day
				// Get the price of the module, Average in 2 last months.

    				$manufactured_rig = UpwellRigs::where('name', $fitted_module->name)->first();

					// Check if we have manufacture cost for rig.

    				if($manufactured_rig) {
					// Define Value that we have calculated from salvage.
    					$priceOfModule = $manufactured_rig->value;

    				} else {
					// Get Value from Market.

    					$to = Carbon::today()->format('Y-m-d'); 
    					$from = Carbon::today()->subMonth(1)->format('Y-m-d');

					// We take the average value of the average values of the past 2 months for calculations.
    					$priceOfModule = MarketPrices::where('type_id', $exists->upm_type_id)
    					->whereBetween('date', [$from, $to])
    					->max('average');
    				}

    				$fittingValue += $priceOfModule;

					// Build the JSON Array
    				$fittingArray[] = [
    					'type_id' => $exists->upm_type_id,
    					'name'   => $exists->upm_name,
    					'price' => $priceOfModule,
    				];

				// END IF

    			} else {

					// The Module didn't exists so we add it to our database.

					// Get the Module ID

    				$module_id = $this->searchEVETypeID($module);

					// Get The Type ID Information from CCP
    				$response = $this->getTypeID($module_id);

					// Drop it In our Database

    				$update_type = UpwellModules::updateOrCreate([
    					'upm_type_id'      				=> $module_id,
    				],[
    					'upm_name'  					=> $response->name,
    				]);

    				$priceOfModule = $this->getMarketPrice($module_id);
    				$fittingValue += $priceOfModule;

    				$fittingArray[] = [
    					'type_id' => $module_id,
    					'name'   => $response->name,
    					'price' => $priceOfModule,
    				];
			// END IF
    			}
			// END OF FOREACH


    		}

		// JSON it up and add it to the structure meta data.
    		$addFittingToStructure = KnownStructures::updateOrCreate([
    			'str_structure_id_md5'      	=> $structure->str_structure_id_md5,
    		],[
    			'str_fitting'  					=> json_encode($fittingArray),
    			'str_value'						=> $fittingValue,
    			'str_has_no_fitting' 			=> "Fitted",
    			'str_market'					=> $market,
    			'str_capital_shipyard'			=> $capital_shipyard,
    			'str_hyasyoda'					=> $hyasyoda,
    			'str_invention'					=> $invention,
    			'str_manufacturing'				=> $manufacturing,
    			'str_research'					=> $research,
    			'str_supercapital_shipyard'		=> $supercapital_shipyard,
    			'str_biochemical'				=> $biochemical,
    			'str_hybrid'					=> $hybrid,
    			'str_moon_drilling'				=> $moon_drilling,
    			'str_reprocessing'				=> $reprocessing,
    			'str_point_defense'				=> $point_defense,
    			'str_dooms_day'					=> $dooms_day,
    			'str_guide_bombs'				=> $guide_bombs,
    			'str_anti_cap'					=> $anti_cap,
    			'str_anti_subcap'				=> $anti_subcap,
    			'str_t2_rigged'					=> $t2_rigged,
    			'str_cloning'					=> $cloning,
    			'str_composite'					=> $composite,
    		]);

    	}
    	$bar->finish();
    }







    public function searchEVE($search)
    {

    	try {
    		$ammended = str_replace(" ", "%20", $search);
    		$response = json_decode(file_get_contents('https://esi.evetech.net/latest/search/?categories=corporation&datasource=tranquility&language=en-us&search=' . $ammended . '&strict=true'));
    		return $response->corporation['0'];
    	} catch (Exception $e) {

    		return redirect()->back()
    		->withErrors('Invalid Fitting');

    	}
    }

    public function searchEVETypeID($search)
    {
    	try {
    		$ammended = str_replace(" ", "%20", $search);
    		$response = json_decode(file_get_contents('https://esi.evetech.net/latest/search/?categories=inventory_type&datasource=tranquility&language=en-us&search=' . $ammended . '&strict=true'));

    		return $response->inventory_type['0'];
    	} catch (Exception $e) {

    		return redirect()->back()
    		->withErrors('Invalid Fitting');

    	}
    }

    public function getMarketPrice($type_id)
    {
    	try {
    		$marketSearch = collect(json_decode(file_get_contents('https://esi.evetech.net/v1/markets/10000002/history/?type_id=' . $type_id), true));
    		$value = $marketSearch->max('average');

    		return $value;
    	} catch (Exception $e) {

    		return redirect()->back()
    		->withErrors('Invalid Fitting');

    	}
    }




    /**
     * Execute the console command.
     *
     * @return mixed
     */
	public function getTypeID($type_id) { // End Point: https://esi.evetech.net/ui/#/Universe/get_universe_types_type_id

		$configuration = Configuration::getInstance();

		$client_id = config('eve.client_id');
		$secret_key = config('eve.secret_key');

		$esi = new Eseye();

		try { 

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

}