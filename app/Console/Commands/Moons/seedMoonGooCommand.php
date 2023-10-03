<?php

namespace Vanguard\Console\Commands\Moons;

use Illuminate\Console\Command;

use Vanguard\MoonGoo;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class seedMoonGooCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moons:seed:goo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed EVE Moon Goo into DB';

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

        '16634',        // Atomspheric Gases
        '16643',        // Cadmium
        '16647',        // Caesium
        '16641',        // Chromium
        '16640',        // Cobalt
        '16650',        // Dysprosium
        '16635',        // Evaporite Deposits
        '16648',        // Hafnium
        '16633',        // Hydrocarbons
        '16646',        // Mercury
        '16651',        // Neodymium
        '16644',        // Platinum
        '16652',        // Promethium
        '16639',        // Scandium
        '16636',        // Silicates
        '16649',        // Technetium
        '16653',        // Thulium
        '16638',        // Titanium
        '16637',        // Tungsten
        '16642'         // Vanadium


    ];


    foreach ($typeIDs as $MoonGoo) {

            $this->getMoonGooInformation($MoonGoo);           // Cycle Array and Send TypeID Materials to save into DB.

        }


    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function getMoonGooInformation($MoonGoo) { // End Point: https://esi.evetech.net/ui/#/Universe/get_universe_types_type_id

        $configuration = Configuration::getInstance();

        $client_id = config('eve.client_id');
        $secret_key = config('eve.secret_key');

        $esi = new Eseye();

        try { 

            $MoonGooDetails = $esi->invoke('get', '/universe/types/{type_id}/', [   
                'type_id' => $MoonGoo,
            ]);

            $updateMoonGoo = MoonGoo::updateOrCreate([
                'type_id'                   => $MoonGoo,
            ],[
                'name'                      => $MoonGooDetails->name,
                'description'               => $MoonGooDetails->description,
                'group_id'                  => $MoonGooDetails->group_id,
                'icon_id'                   => $MoonGooDetails->icon_id,
                'portion_size'              => $MoonGooDetails->portion_size,
            ]);

            $this->info('MoonGoos Added: ' . $MoonGooDetails->name);

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
