<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;

use Auth;
use Exception;
use Carbon\Carbon;
use Vanguard\ESITokens;
use Vanguard\Characters;
use Vanguard\Ledger;
use Vanguard\SolarSystems;
use Vanguard\HarvestedMaterials;
use Vanguard\RefinedMaterials;
use Vanguard\MarketPrices;
use Vanguard\Moons;
use Vanguard\NewMoons;
use Vanguard\MoonScans;


use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;



class updateGooToMoonDataAndMarketPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:updateGooToMoonDataAndMarketPrices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds Moon Scan Data to Moons and Prices at Current Market Prices.';

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

        $this->info('Adding Moon Goo Data');
        MoonScans::groupBy('moon_id')->orderBy('updated_at', 'DESC')->chunk(100, function ($moon_scans) {

    	//$bar = $this->output->createProgressBar(count($moon_scans));
    	//$bar->start();

           foreach ($moon_scans as $moon) {

            $atmo_gases = 0;
            $cadmium = 0;
            $caesium = 0;
            $chromium = 0;
            $cobalt = 0;
            $dysprosium = 0;
            $eva_depo = 0;
            $hafnium = 0;
            $hydrocarbons = 0;
            $mercury = 0;
            $neodymium = 0;
            $platinum = 0;
            $promethium = 0;
            $scandium = 0;
            $silicates = 0;
            $technetium = 0;
            $thulium = 0;
            $titanium = 0;
            $tungsten = 0;
            $vanadium = 0;

            $r_value[] = 0;

    		// Get the Moon Data, I.e which ores and distrubtion.

            $moon_data = MoonScans::where('moon_id', $moon->moon_id)->get();

    		// Returns: moon_product, moon_quantity, moon_ore_type_id
    		// Cycle each ore and get the refine details.
    		// Build JSON array for entry into : moon_dist_ore

            $moon_dist_ore = array();
            $moon_ore_refine = array();
            $extraction_value = 40000;

            foreach ($moon_data as $ore) {

    			// Which Ore is on this moon.
    			// Build Array


             $refine =           $this->getRefineDetails($ore->moon_ore_type_id);

             $extraction_per_hour_m3 = $extraction_value * $ore->moon_quantity;

    			// Moon Drill = 20,000 m3 per hour.
    			// Monzaite = 60% Distriubtion
    			// 20,000 * 0.6 = 12,000m3 per hour extraction

             $units_per_hour = $extraction_per_hour_m3 / $refine->volume;
    			// To get the units, we take the m3 / volume of each Ore.
    			// 12000 m3 / 10 m3 = 1200 Units per hour.

    			// Now we calculate what it refines to.

             foreach($refine->refined as $type_id => $minerals) {

    				/*{#10488
					  +"name": "Bitumens"
					  +"typeID": 45492
					  +"volume": 10
					  +"refined": {#10493
					    +"34": 6000
					    +"35": 6000
					    +"36": 400
					    +"16633": 65
					  }
					  +"portionSize": 100
					}
					*/

    				// Which Minerals refine for each Ore.
    				// At this stage we should check for the highest goo R value and set it.
    				// moon_r_rating.
    				// Build JSON array for entry into : moon_ore_refine_value
					$r_value[] =		$this->gooRValue($type_id);
                    
					// Get Mineral Value from Market Database.
					$value_per_unit = $this->getMarketPrices($type_id, Carbon::now()->subDay(2)->format('Y-m-d'));

					// Max Refine Rate, T2 Rigged Tatara.
					$max_refine = 0.893;

					// We need the portion size to find out how many units are required to refine.
					$portion_size = $refine->portionSize;

					// Now we Calculate the Refine per Hour by working out the following
					// Mineral * Unit Value (From Market Data) * Units per Hour of Extraction(1200) / Portion Size (How Many Units Required to Refine i.e 100) * Max Refine (0.893)
					$refine_value_per_hour = ($minerals * $value_per_unit * $units_per_hour) / $portion_size * $max_refine;

					// Add the amunt to this array and sum it at the end of total value per hour.
					$value_of_moon_1_hour[] = $refine_value_per_hour;

                    $material_name = $this->getMinerialName($type_id);

                    // Lets Map the Goo to our Moon

                    if($material_name == "Atmospheric Gases") { $atmo_gases = 1; }
                    if($material_name == "Cadmium") { $cadmium = 1; }
                    if($material_name == "Caesium") { $caesium = 1; }
                    if($material_name == "Chromium") { $chromium = 1; }
                    if($material_name == "Cobalt") { $cobalt = 1; }
                    if($material_name == "Dysprosium") { $dysprosium = 1; }
                    if($material_name == "Evaporite Deposits") { $eva_depo = 1; }
                    if($material_name == "Hafnium") { $hafnium = 1; }
                    if($material_name == "Hydrocarbons") { $hydrocarbons = 1; }
                    if($material_name == "Mercury") { $mercury = 1; }
                    if($material_name == "Neodymium") { $neodymium = 1; }
                    if($material_name == "Platinum") { $platinum = 1; }
                    if($material_name == "Promethium") { $promethium = 1; }
                    if($material_name == "Scandium") { $scandium = 1; }
                    if($material_name == "Silicates") { $silicates = 1; }
                    if($material_name == "Technetium") { $technetium = 1; }
                    if($material_name == "Thulium") { $thulium = 1; }
                    if($material_name == "Titanium") { $titanium = 1; }
                    if($material_name == "Tungsten") { $tungsten = 1; }
                    if($material_name == "Vanadium") { $vanadium = 1; }

					// Build Up JSON for Refine

                    $refined_materials[$type_id] = [				

                      'type_id' => $type_id,
                      'r_value' => $this->gooRValue($type_id),
                      'name' => $material_name,
                      'refine_amount' => $minerals,
                      'value_per_unit' => $value_per_unit,
                      'refine_amount_per_hour' => $minerals * $units_per_hour,
                      'refine_value_per_hour' => $refine_value_per_hour,
                      'refine_1_day' => $refine_value_per_hour * 24,
                      'refine_30_days' => $refine_value_per_hour * 24 * 30,
                      'refine_56_days' => $refine_value_per_hour * 24 * 56,

                  ];

                  

					// END FOREACH
                  
              }
				// Build Array

              $moon_dist_ore[$ore->moon_ore_type_id] = [

               'type_id' => $ore->moon_ore_type_id,
               'name' => $ore->moon_product,
               'distribution' => $ore->moon_quantity,
               'volume' => $refine->volume,
               'portionSize' => $refine->portionSize,
               'extraction_per_hour_m3' => $extraction_per_hour_m3,
               'units_per_hour' => $units_per_hour,
               'refined' => $refined_materials,
           ];

				// Unset

           unset($refined_materials);

       }

			// Insert R Value

       $moon_value = array_sum($value_of_moon_1_hour);

       $update_moon = NewMoons::where('moon_id', $moon->moon_id)->first();

       $update_moon->moon_r_rating = max($r_value);
       $update_moon->moon_dist_ore = json_encode($moon_dist_ore);
       $update_moon->moon_value_24_hour = $moon_value  * 24;
       $update_moon->moon_value_7_day = $moon_value  * 24 * 7;
       $update_moon->moon_value_30_day = $moon_value  * 24 * 30;
       $update_moon->moon_value_56_day = $moon_value  * 24 * 56;
       $update_moon->moon_atmo_gases = $atmo_gases;
       $update_moon->moon_cadmium = $cadmium;
       $update_moon->moon_caesium = $caesium;
       $update_moon->moon_chromium = $chromium;
       $update_moon->moon_cobalt = $cobalt;
       $update_moon->moon_dysprosium = $dysprosium;
       $update_moon->moon_eva_depo = $eva_depo;
       $update_moon->moon_hafnium = $hafnium;
       $update_moon->moon_hydrocarbons = $hydrocarbons;
       $update_moon->moon_mercury = $mercury;
       $update_moon->moon_neodymium = $neodymium;
       $update_moon->moon_platinum = $platinum;
       $update_moon->moon_promethium = $promethium;
       $update_moon->moon_scandium = $scandium;
       $update_moon->moon_silicates = $silicates;
       $update_moon->moon_technetium = $technetium;
       $update_moon->moon_thulium = $thulium;
       $update_moon->moon_titanium = $titanium;
       $update_moon->moon_tungsten = $tungsten;
       $update_moon->moon_vanadium = $vanadium;

       $update_moon->save();

       $this->info('Moon Name: ' . $update_moon->moon_name . ' : Rating : ' . max($r_value) . ' : 30 Day Value: ' . number_format($moon_value  * 24 * 30, 2));
			//$bar->advance();

       unset($value_of_moon_1_hour);
       unset($r_value);


		// END FOREACH
   }
         // End Chunk
});
		//$bar->finish();

}



    /// ALL CALC SHIT BELOW HERE DO NOT TOUCH

    /**
     * Get Solar System Name
     *
     * @return SolarSystem
     */
    public function getSolarSystemName($system_id) {

    	$solarSystem = SolarSystems::where('system_id', $system_id)
    	->first();

    	return $solarSystem['name'];
    }

    /**
     * Get Character Name
     *
     * @return Character Name
     */
    public function getCharacterName($character_id) {

    	$character = Characters::where('character_id', $character_id)
    	->first();

    	return $character['name'];

    }

    /**
     * Get Ore Name
     *
     * @return Ore Name
     */
    public function getOreName($type_id) {

    	$ore = HarvestedMaterials::where('type_id', $type_id)
    	->first();

    	return $ore['name'];

    }

    /**
     * Get Ore Volume
     *
     * @return Ore Volume
     */
    public function getOreVolume($type_id) {

    	$ore = HarvestedMaterials::where('type_id', $type_id)
    	->first();

    	return $ore['volume'];

    }

    /**
     * Get Ore Volume
     *
     * @return Ore Volume
     */
    public function getMinerialName($type_id) {

    	$ore = RefinedMaterials::where('type_id', $type_id)
    	->first();

    	return $ore['name'];

    }

    /**
     * Get Refine Details
     *
     * @return Ore Name
     */
    public function getRefineDetails($type_id) {

    	$ore = HarvestedMaterials::where('type_id', $type_id)
    	->first();

    	$json = json_decode($ore['json']);

    	return $json;
    }

    /**
     * Get Refine Details
     *
     * @return Ore Name
     */
    public function getMarketPrices($type_id, $date) {

        $from = Carbon::parse($date)->subWeek(1)->format('Y-m-d');

        $price = MarketPrices::where('type_id', $type_id)
        ->whereBetween('date', [$from, $date])
        ->avg('average');

        return $price;
    }

    /**
     * Calculate Estimated Value
     *
     * @return Ore Name
     *
     * Type ID of Ore
     * Prices Array
     * Quantity String
     * Minerals Array
     */
    public function calculateEstimatedValue($type_id, $prices, $quantity, $minerals) {

    	$refinePercentage = 0.893396;
    	$sum = array();
    	$estimatedValue = 0;
    	$refinedValues = 0;

    	$portionSize = HarvestedMaterials::where('type_id', $type_id)->first();

        // Cycles all Minerals within the JSON for the Ledger Row
    	foreach ($minerals as $index => $refined)
    	{

            //Cycles all Mineral Prices within the JSON for the Ledger Row
    		foreach($prices as $mineral_id => $price) {

                // Matching the Price with the Ore
    			if($index == $mineral_id) {

                // Work out the price
    				$RefinePrice = $price * $refined * $quantity / $portionSize->portion_size * $refinePercentage;

    				$sum[] = $RefinePrice;

    			}

    		}

    	}

    	return array_sum($sum);

    }



    /**
     * Calculate Estimated Tax Value
     *
     * @return Tax
     *
     * Value
     */
    public function calculateTax($estimatedValue) {
    	$taxRate = 0.085;
    	$tax = $estimatedValue * $taxRate;
    	return $tax;
    }

    /**
    * Ignore these gas/ore.
    *
    * @return boolean
    */  
    public function ignoreThisOre($type_id) {

    	$ignore = [
    		// Gas
    		'25268',
    		'28694',
    		'25279',
    		'28695',
    		'25275',
    		'28696',
    		'25273',
    		'28697',
    		'25277',
    		'28698',
    		'25276',
    		'28699',
    		'25278',
    		'28700',
    		'25274',
    		'28701',
    		'30375',
    		'30376',
    		'30377',
    		'30370',
    		'30378',
    		'30371',
    		'30372',
    		'30373',
    		'30374',
    		// Empire Ores NPC
    		'46253',
    		'46259',
    		'46257',
    		'46255',
    		'46258',
    		'46256',
    		'46254',
    		'46252',
    		'50015',
    		'48916',
    		'49789',
    		'49787',
    		'28618',
    		'28619',
    		'28617',
    	];


    	if (in_array($type_id, $ignore)) {
    		return false;
    	} else {
    		return true;
    	}
    }

    /**
    * Ignore these gas/ore.
    *
    * @return boolean
    */  
    public function gooRValue($type_id) {

    	$r64 = [
    		'16650',
    		'16651',
    		'16652',
    		'16653',
    	];

    	$r32 = [
    		'16646',
    		'16647',
    		'16649',
    		'16648',
    	];

    	$r16 = [
    		'16641',
    		'16642',
    		'16643',
    		'16644',
    	];
    	$r8 = [
    		'16637',
    		'16638',
    		'16639',
    		'16640',
    	];

    	$r4 = [
    		'16633',
    		'16634',
    		'16635',
    		'16636',

    	];

    	if (in_array($type_id, $r64)) {
    		return 64;
    	}

    	if (in_array($type_id, $r32)) {
    		return 32;
    	}

    	if (in_array($type_id, $r16)) {
    		return 16;
    	}

    	if (in_array($type_id, $r8)) {
    		return 8;
    	}

    	if (in_array($type_id, $r4)) {
    		return 4;
    	}


    	return 0;


    }
}