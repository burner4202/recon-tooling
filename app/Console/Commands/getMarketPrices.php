<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;

use Datetime;
use DB;
use Auth;
use Carbon\Carbon;

use GuzzleHttp\Client;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

use Vanguard\Salvage;
use Vanguard\MarketPrices;
use Vanguard\UpwellModules;
use Vanguard\RefinedMaterials;

class getMarketPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:getMarketPrices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets Market Prices';

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

        $region = 10000002; // The Forge
        $url = 'https://esi.evetech.net/v1/markets/';

        
        $salvage = Salvage::all();
        $modules = UpwellModules::all();
        $minerals = RefinedMaterials::all();

        $this->info('Updating Market Prices');
        $bar = $this->output->createProgressBar(count($salvage) + count($modules) + count($minerals));
        $bar->start();
        $today = Carbon::today();
        $from = $today->subWeek(1);


        foreach ($salvage as $type_id) {

        	$prices = collect(json_decode(file_get_contents($url . $region . '/history/?type_id=' . $type_id->type_id), true));


        	foreach ($prices as $price) {

        		$current = Carbon::parse($price['date']);

        		if($from <= $price['date']) {

                $market_id = md5($price['date'] . $type_id->type_id);   // Unique Date/Type ID Value   

                $updatePrice = MarketPrices::updateOrCreate([
                	'market_id'         => $market_id,
                ],[
                	'type_id'           => $type_id->type_id,
                	'date'              => $price['date'],
                	'highest'           => $price['highest'],
                	'lowest'            => $price['lowest'],
                	'average'           => $price['average'],
                	'order_count'       => $price['order_count'],
                	'volume'            => $price['volume'],

                ]);
            }

        }



        $bar->advance();
        // END FOREACH
    }

    foreach ($modules as $type_id) {

    	$prices = collect(json_decode(file_get_contents($url . $region . '/history/?type_id=' . $type_id->upm_type_id), true));


    	foreach ($prices as $price) {

    		$current = Carbon::parse($price['date']);

    		if($from <= $price['date']) {

                $market_id = md5($price['date'] . $type_id->upm_type_id);   // Unique Date/Type ID Value   

                $updatePrice = MarketPrices::updateOrCreate([
                	'market_id'         => $market_id,
                ],[
                	'type_id'           => $type_id->upm_type_id,
                	'date'              => $price['date'],
                	'highest'           => $price['highest'],
                	'lowest'            => $price['lowest'],
                	'average'           => $price['average'],
                	'order_count'       => $price['order_count'],
                	'volume'            => $price['volume'],

                ]);
            }

        }



        $bar->advance();
            // END FOREACH
    }

    foreach ($minerals as $type_id) {

    	$prices = collect(json_decode(file_get_contents($url . $region . '/history/?type_id=' . $type_id->type_id), true));


    	foreach ($prices as $price) {

    		$current = Carbon::parse($price['date']);

    		if($from <= $price['date']) {

				$market_id = md5($price['date'] . $type_id->type_id);   // Unique Date/Type ID Value   

				$updatePrice = MarketPrices::updateOrCreate([
					'market_id'      	=> $market_id,
				],[
					'type_id'    		=> $type_id->type_id,
					'date'   			=> $price['date'],
					'highest'           => $price['highest'],
					'lowest'            => $price['lowest'],
					'average'           => $price['average'],
					'order_count'       => $price['order_count'],
					'volume'            => $price['volume'],

				]);
            }

        }



        $bar->advance();
         // END FOREACH
    }





    $bar->finish();
}


}
