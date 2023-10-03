<?php

namespace Vanguard\Console\Commands\Moons;

use Illuminate\Console\Command;

use Vanguard\RefinedMaterials;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class seedRefinedMaterialsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moons:seed:refined:materials';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed Database with EVE Refined Materials from Harvested Products';

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
        $refinedMaterials = [
        // Minerals
        '37',           // Isogen
        '40',           // Megacyte
        '36',           // Mexallon
        '11399',        // Morphite
        '38',           // Nocxium
        '35',           // Pyerite
        '34',           // Tritanium
        '39',           // Zydrine

        // Ice Products
        '16272',        // Heavy Water
        '16274',        // Helium Isotopes
        '17889',        // Hydrogen Isotopes
        '16273',        // Liquid Ozone
        '17888',        // Nitrogen Isotopes
        '17887',        // Oxygen Isotopes
        '16275',        // Strontium Clathrates

        // Raw Moon Materials
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


    foreach ($refinedMaterials as $material) {
            $this->getInformation($material);             // Cycle Array and Send TypeID Materials to save into DB.
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function getInformation($material) { // End Point: https://esi.evetech.net/ui/#/Universe/get_universe_types_type_id

        $configuration = Configuration::getInstance();

        $client_id = config('eve.client_id');
        $secret_key = config('eve.secret_key');

        $esi = new Eseye();

        try { 

            $esi = $esi->invoke('get', '/universe/types/{type_id}/', [   
                'type_id' => $material,
            ]);

            $updateMaterials = RefinedMaterials::updateOrCreate([
                'type_id'                   => $material,
            ],[
                'name'                      => $esi->name,
                'description'               => $esi->description,
                'group_id'                  => $esi->group_id,
                'icon_id'                   => $esi->icon_id,
                'portion_size'              => $esi->portion_size,
            ]);

            $this->info('Material Added: ' . $esi->name);

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
