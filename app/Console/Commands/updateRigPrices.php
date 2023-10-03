<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;

use Datetime;

use Auth;
use Log;
use Carbon\Carbon;
use Vanguard\User;
use Vanguard\UpwellRigs;
use Vanguard\Salvage;
use Vanguard\MarketPrices;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;


class updateRigPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:updateRigPrices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates Rig Prices';

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


        $now = Carbon::now();
        $yesterday = $now->subDay(2);

        $rigs = UpwellRigs::get();

        $this->info('Updating Rig Costs');
        //$bar = $this->output->createProgressBar(count($rigs));
        //$bar->start();

        foreach ($rigs as $rig) {

        // Get Materials
        // Get Prices
        // Get Value

            $material =   $this->getMaterialDetails($rig->type_id);
            $prices =     $this->getMarketPrices($material, $yesterday->format('Y-m-d'));
            $item_prices = $this->calculateItemValues($rig->type_id, $prices, $material->materials);
            $value =     $this->calculateValue($rig->type_id, $prices, $material->materials);

            $rig->item_prices = json_encode($prices);
            $rig->sum_prices = json_encode($item_prices);
            $rig->value = $value;
            $rig->save();

            //$bar->advance();
            $this->info(' ' . number_format($value, 2) . ' ~~~~ ' . $rig->name);

        }

        //$bar->finish();
    }


    /**
     * Get Material Details
     *
     * @return Material Name
     */
    public function getMaterialDetails($type_id) {

        $rig = UpwellRIgs::where('type_id', $type_id)
        ->first();

        $json = json_decode($rig['meta_data']);

        return $json;
    }

    /**
     * Get Refine Details
     *
     * @return Ore Name
     */
    public function getMarketPrices($minerals, $date) {

        $prices = [];

        //$json = json_decode($minerals);

        foreach ($minerals->materials as $index => $mineral) {

            $price = MarketPrices::where('type_id', $index)
            ->where('date', $date)
            ->first();

            $prices[$index] = $price['average'];

        }
        
        return $prices;
    }

    /**
     * Calculate Value
     *
     * @return Ore Name
     *
     * Type ID of Ore
     * Prices Array
     * Quantity String
     * Minerals Array
     */
    public function calculateValue($type_id, $prices, $materials) {

        $sum = array();

        // Cycles all materials within the JSON for the Rigs
        foreach ($materials as $index => $item)
        {

            // Cycles all materials within the JSON for the Rigs
            foreach($prices as $type_id => $price) {

                // Matching the Price with the Ore
                if($index == $type_id) {

                // Work out the price
                    $build_cost = $price * $item;

                    $sum[] = $build_cost;

                }

            }

        }

        return array_sum($sum);

    }

    /**
     * Calculate Value
     *
     * @return Ore Name
     *
     * Type ID of Ore
     * Prices Array
     * Quantity String
     * Minerals Array
     */
    public function calculateItemValues($type_id, $prices, $materials) {

        $item_prices = array();

        // Cycles all materials within the JSON for the Rigs
        foreach ($materials as $index => $item)
        {

            // Cycles all materials within the JSON for the Rigs
            foreach($prices as $type_id => $price) {

                // Matching the Price with the Ore
                if($index == $type_id) {

                // Work out the price
                    $build_cost = $price * $item;

                    $item_prices[$type_id] = $build_cost;

                }

            }

        }

        return $item_prices;

    }


}