<?php

namespace Vanguard\Console\Commands\Moons;

use Illuminate\Console\Command;

use Vanguard\Minerals;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;


class seedMineralsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moons:seed:minerals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed EVE Minerals into DB';

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

        // Minerals
        '37',           // Isogen
        '40',           // Megacyte
        '36',           // Mexallon
        '11399',        // Morphite
        '38',           // Nocxium
        '35',           // Pyerite
        '34',           // Tritanium
        '39',           // Zydrine


    ];


        foreach ($typeIDs as $mineral) {

            $this->getMineralInformation($mineral);           // Cycle Array and Send TypeID Materials to save into DB.

        }


    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function getMineralInformation($minerals) { // End Point: https://esi.evetech.net/ui/#/Universe/get_universe_types_type_id

        $configuration = Configuration::getInstance();

        $client_id = config('eve.client_id');
        $secret_key = config('eve.secret_key');

        $esi = new Eseye();

        try { 

            $mineralsDetails = $esi->invoke('get', '/universe/types/{type_id}/', [   
                'type_id' => $minerals,
            ]);

            $updateminerals = Minerals::updateOrCreate([
                'type_id'                   => $minerals,
            ],[
                'name'                      => $mineralsDetails->name,
                'description'               => $mineralsDetails->description,
                'group_id'                  => $mineralsDetails->group_id,
                'icon_id'                   => $mineralsDetails->icon_id,
                'portion_size'              => $mineralsDetails->portion_size,
            ]);

            $this->info('Minerals Added: ' . $mineralsDetails->name);

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
