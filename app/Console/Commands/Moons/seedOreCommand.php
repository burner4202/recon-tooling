<?php

namespace Vanguard\Console\Commands\Moons;

use Illuminate\Console\Command;

use Vanguard\Ore;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class seedOreCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moons:seed:ore';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed EVE Ores into the Database';

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
        $materials['22'] = [

            'name'          => 'Arkonor',
            'typeID'        => 22,
            'volume'        => 16,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Megacyte'      => 160,
                'Mexallon'      => 1250,
                'Tritanium'     => 11000
            ]
        ];

        $materials['17425'] = [

            'name'          => 'Crimson Arkonor',
            'typeID'        => 17425,
            'volume'        => 16,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Megacyte'      => 168,
                'Mexallon'      => 1312,
                'Tritanium'     => 11550
            ]
        ];

        $materials['46678'] = [

            'name'          => 'Flawless Arkonor',
            'typeID'        => 46678,
            'volume'        => 16,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Megacyte'      => 184,
                'Mexallon'      => 1437,
                'Tritanium'     => 12650
            ]
        ];

        $materials['17426'] = [

            'name'          => 'Prime Arkonor',
            'typeID'        => 17426,
            'volume'        => 16,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Megacyte'      => 176,
                'Mexallon'      => 1375,
                'Tritanium'     => 12100
            ]
        ];

        $materials['1223'] = [

            'name'          => 'Bisot',
            'typeID'        => 1223,
            'volume'        => 16,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Megacyte'      => 50,
                'Pyerite'       => 6000,
                'Zydrine'       => 225
            ]
        ];

        $materials['46676'] = [

            'name'          => 'Cubic Bistot',
            'typeID'        => 46676,
            'volume'        => 16,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Megacyte'      => 57,
                'Pyerite'       => 6900,
                'Zydrine'       => 259
            ]
        ];

        $materials['17429'] = [
            'name'          => 'Monoclinic Bistot',
            'typeID'        => 17429,
            'volume'        => 16,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Megacyte'      => 55,
                'Pyerite'       => 6600,
                'Zydrine'       => 247
            ]
        ];

        $materials['17428'] = [

            'name'          => 'Monoclinic Bistot',
            'typeID'        => 17428,
            'volume'        => 16,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Megacyte'      => 52,
                'Pyerite'       => 6300,
                'Zydrine'       => 236
            ]
        ];

        $materials['1225'] = [

            'name'          => 'Crokite',
            'typeID'        => 1225,
            'volume'        => 16,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Nocxium'       => 380,
                'Tritanium'     => 10500,
                'Zydrine'       => 67
            ]
        ];

        $materials['17433'] = [

            'name'          => 'Crystalline Crokite',
            'typeID'        => 17433,
            'volume'        => 16,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Nocxium'       => 418,
                'Tritanium'     => 11550,
                'Zydrine'       => 74
            ]
        ];

        $materials['46677'] = [

            'name'          => 'Pellucid Crokite',
            'typeID'        => 46677,
            'volume'        => 16,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Nocxium'       => 437,
                'Tritanium'     => 12075,
                'Zydrine'       => 77
            ]
        ];

        $materials['1232'] = [

            'name'          => 'Dark Ochre',
            'typeID'        => 1232,
            'volume'        => 8,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Isogen'        => 800,
                'Nocxium'       => 60,
                'Tritanium'     => 5000
            ]
        ];

        $materials['46675'] = [

            'name'          => 'Jet Ochre',
            'typeID'        => 46675,
            'volume'        => 8,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Isogen'        => 920,
                'Nocxium'       => 69,
                'Tritanium'     => 5750
            ]
        ];

        $materials['17437'] = [

            'name'          => 'Obsidian Ochre',
            'typeID'        => 17437,
            'volume'        => 8,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Isogen'        => 880,
                'Nocxium'       => 66,
                'Tritanium'     => 5500
            ]
        ];

        $materials['17436'] = [

            'name'          => 'Obsidian Ochre',
            'typeID'        => 17436,
            'volume'        => 8,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Isogen'        => 840,
                'Nocxium'       => 63,
                'Tritanium'     => 5250
            ]
        ];

        $materials['46679'] = [

            'name'          => 'Brilliant Gneiss',
            'typeID'        => 46679,
            'volume'        => 5,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Isogen'        => 172,
                'Mexallon'      => 1380,
                'Pyerite'       => 1265
            ]
        ];

        $materials['1229'] = [

            'name'          => 'Gneiss',
            'typeID'        => 1229,
            'volume'        => 5,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Isogen'        => 150,
                'Mexallon'      => 1200,
                'Pyerite'       => 1100
            ]
        ];

        $materials['17865'] = [

            'name'          => 'Iridescent Gneiss',
            'typeID'        => 17865,
            'volume'        => 5,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Isogen'        => 157,
                'Mexallon'      => 1260,
                'Pyerite'       => 1155
            ]
        ];

        $materials['17866'] = [

            'name'          => 'Prismatic Gneiss',
            'typeID'        => 17866,
            'volume'        => 5,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Isogen'        => 165,
                'Mexallon'      => 1320,
                'Pyerite'       => 1210
            ]
        ];

        $materials['17441'] = [

            'name'          => 'Glazed Hedbergite',
            'typeID'        => 17441,
            'volume'        => 3,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Isogen'        => 110,
                'Nocxium'       => 55,
                'Pyerite'       => 550,
                'Zydrine'       => 10
            ]
        ];

        $materials['21'] = [

            'name'          => 'Hedbergite',
            'typeID'        => 21,
            'volume'        => 3,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Isogen'        => 100,
                'Nocxium'       => 50,
                'Pyerite'       => 500,
                'Zydrine'       => 9
            ]
        ];

        $materials['46680'] = [

            'name'          => 'Lustrous Hedbergite',
            'typeID'        => 46680,
            'volume'        => 3,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Isogen'        => 115,
                'Nocxium'       => 57,
                'Pyerite'       => 575,
                'Zydrine'       => 11
            ]
        ];

        $materials['17440'] = [

            'name'          => 'Vitric Hedbergite',
            'typeID'        => 17440,
            'volume'        => 3,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Isogen'        => 105,
                'Nocxium'       => 52,
                'Pyerite'       => 525,
                'Zydrine'       => 10
            ]
        ];

        $materials['1231'] = [

            'name'          => 'Hemorphite',
            'typeID'        => 1231,
            'volume'        => 3,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Isogen'        => 50,
                'Nocxium'       => 60,
                'Tritanium'     => 1100,
                'Zydrine'       => 7
            ]
        ];

        $materials['17445'] = [

            'name'          => 'Radiant Hemorphite',
            'typeID'        => 17445,
            'volume'        => 3,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Isogen'        => 55,
                'Nocxium'       => 66,
                'Tritanium'     => 1210,
                'Zydrine'       => 8
            ]
        ];

        $materials['46681'] = [

            'name'          => 'Scintillating Hemorphite',
            'typeID'        => 46681,
            'volume'        => 3,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Isogen'        => 57,
                'Nocxium'       => 69,
                'Tritanium'     => 1265,
                'Zydrine'       => 8
            ]
        ];

        $materials['17444'] = [

            'name'          => 'Vivid Hemorphite',
            'typeID'        => 17444,
            'volume'        => 3,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Isogen'        => 52,
                'Nocxium'       => 63,
                'Tritanium'     => 1155,
                'Zydrine'       => 8
            ]
        ];

        $materials['46682'] = [

            'name'          => 'Immaculate Jaspet',
            'typeID'        => 46682,
            'volume'        => 2,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Mexallon'      => 201,
                'Nocxium'       => 43,
                'Zydrine'       => 4
            ]
        ];

        $materials['1226'] = [

            'name'          => 'Jaspet',
            'typeID'        => 1226,
            'volume'        => 2,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Mexallon'      => 175,
                'Nocxium'       => 37,
                'Zydrine'       => 4
            ]
        ];

        $materials['17449'] = [

            'name'          => 'Pristine Jaspet',
            'typeID'        => 17449,
            'volume'        => 2,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Mexallon'      => 192,
                'Nocxium'       => 41,
                'Zydrine'       => 4
            ]
        ];

        $materials['17448'] = [


            'name'          => 'Pure Jaspet',
            'typeID'        => 17448,
            'volume'        => 2,
            'portionSize'   => 100,
            
            'refinedMinerals' =>   [
                'Mexallon'      => 184,
                'Nocxium'       => 39,
                'Zydrine'       => 4
            ]
        ];

        $materials['17453'] = [

            'name'          => 'Fiery Kernite',
            'typeID'        => 17453,
            'volume'        => 1.2,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Isogen'        => 74,
                'Mexallon'      => 147,
                'Tritanium'     => 74
            ]
        ];

        $materials['20'] = [

            'name'          => 'Kernite',
            'typeID'        => 20,
            'volume'        => 1.2,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Isogen'        => 67,
                'Mexallon'      => 133,
                'Tritanium'     => 67
            ]
        ];


        $materials['17452'] = [


            'name'          => 'Luminous Kernite',
            'typeID'        => 17452,
            'volume'        => 1.2,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Isogen'        => 70,
                'Mexallon'      => 140,
                'Tritanium'     => 70
            ]
        ];

        $materials['17869'] = [


            'name'          => 'Magma Mercoxit',
            'typeID'        => 17869,
            'volume'        => 40,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Morphite'      => 157,
            ]
        ];

        $materials['11396'] = [


            'name'          => 'Mercoxit',
            'typeID'        => 11396,
            'volume'        => 40,
            'portionSize'   => 100,
            
            'refinedMinerals' =>   [
                'Morphite'      => 150,
            ]
        ];

        $materials['17870'] = [


            'name'          => 'Viteous Mercoxit',
            'typeID'        => 17870,
            'volume'        => 40,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Morphite'      => 165,
            ]
        ];

        $materials['17868'] = [


            'name'          => 'Golden Omber',
            'typeID'        => 17868,
            'volume'        => 0.6,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Isogen'        => 47,
                'Pyerite'       => 55,
                'Tritanium'     => 440
            ]
        ];

        $materials['1227'] = [


            'name'          => 'Omber',
            'typeID'        => 1227,
            'volume'        => 0.6,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Isogen'        => 42,
                'Pyerite'       => 50,
                'Tritanium'     => 400
            ]
        ];

        $materials['17867'] = [


            'name'          => 'Silvery Omber',
            'typeID'        => 17867,
            'volume'        => 0.6,
            'portionSize'   => 100,
            
            'refinedMinerals' =>   [
                'Isogen'        => 45,
                'Pyerite'       => 52,
                'Tritanium'     => 420
            ]
        ];

        $materials['17455'] = [


            'name'          => 'Azure Plagioclase',
            'typeID'        => 17455,
            'volume'        => 0.35,
            'portionSize'   => 100,
            
            'refinedMinerals' =>   [
                'Mexallon'      => 56,
                'Pyerite'       => 112,
                'Tritanium'     => 56
            ]
        ];

        $materials['18'] = [


            'name'          => 'Plagioclase',
            'typeID'        => 18,
            'volume'        => 0.35,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Mexallon'      => 53,
                'Pyerite'       => 106,
                'Tritanium'     => 53
            ]
        ];

        $materials['17456'] = [

            'name'          => 'Rich Plagioclase',
            'typeID'        => 17456,
            'volume'        => 0.35,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Mexallon'      => 59,
                'Pyerite'       => 117,
                'Tritanium'     => 59
            ]
        ];

        $materials['46685'] = [


            'name'          => 'Sparkling Plagioclase',
            'typeID'        => 46685,
            'volume'        => 0.35,
            'portionSize'   => 100,
            
            'refinedMinerals' =>   [
                'Mexallon'      => 61,
                'Pyerite'       => 122,
                'Tritanium'     => 61
            ]
        ];

        $materials['1224'] = [


            'name'          => 'Pyroxeres',
            'typeID'        => 1224,
            'volume'        => 0.30,
            'portionSize'   => 100,
            
            'refinedMinerals' =>   [
                'Mexallon'      => 25,
                'Nocxium'       => 2,
                'Pyerite'       => 12,
                'Tritanium'     => 175
            ]
        ];

        $materials['17459'] = [


            'name'          => 'Solid Pyroxeres',
            'typeID'        => 17459,
            'volume'        => 0.30,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Mexallon'      => 26,
                'Nocxium'       => 2,
                'Pyerite'       => 13,
                'Tritanium'     => 184
            ]
        ];

        $materials['17460'] = [


            'name'          => 'Viscous Pyroxeres',
            'typeID'        => 17460,
            'volume'        => 0.30,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Mexallon'      => 27,
                'Nocxium'       => 2,
                'Pyerite'       => 13,
                'Tritanium'     => 193
            ]
        ];

        $materials['17463'] = [


            'name'          => 'Condensed Scordite',
            'typeID'        => 17463,
            'volume'        => 0.15,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Pyerite'       => 91,
                'Tritanium'     => 182
            ]
        ];

        $materials['46687'] = [


            'name'          => 'Glossy Scordite',
            'typeID'        => 46687,
            'volume'        => 0.15,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Pyerite'       => 99,
                'Tritanium'     => 199
            ]
        ];

        $materials['17464'] = [


            'name'          => 'Massive Scordite',
            'typeID'        => 17464,
            'volume'        => 0.15,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Pyerite'       => 95,
                'Tritanium'     => 190
            ]
        ];

        $materials['1228'] = [


            'name'          => 'Scordite',
            'typeID'        => 1228,
            'volume'        => 0.15,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Pyerite'       => 86,
                'Tritanium'     => 173
            ]
        ];

        $materials['17466'] = [


            'name'          => 'Bright Spodumain',
            'typeID'        => 17466,
            'volume'        => 16,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Isogen'        => 236,
                'Mexallon'      => 1102,
                'Pyerite'       => 6326,
                'Tritanium'     => 29400
            ]
        ];

        $materials['46688'] = [


            'name'          => 'Dazzling Spodumain',
            'typeID'        => 46688,
            'volume'        => 16,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Isogen'        => 259,
                'Mexallon'      => 1207,
                'Pyerite'       => 6929,
                'Tritanium'     => 32200
            ]
        ];

        $materials['17467'] = [

            'name'          => 'Gleaming Spodumain',
            'typeID'        => 17467,
            'volume'        => 16,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Isogen'        => 247,
                'Mexallon'      => 1115,
                'Pyerite'       => 6627,
                'Tritanium'     => 30800
            ]
        ];

        $materials['19'] = [

            'name'          => 'Spodumain',
            'typeID'        => 19,
            'volume'        => 16,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Isogen'        => 225,
                'Mexallon'      => 1050,
                'Pyerite'       => 6025,
                'Tritanium'     => 28000
            ]
        ];

        $materials['17470'] = [


            'name'          => 'Concentrated Veldspar',
            'typeID'        => 17470,
            'volume'        => 0.10,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Tritanium'     => 218
            ]
        ];

        $materials['17471'] = [


            'name'          => 'Dense Veldspar',
            'typeID'        => 17471,
            'volume'        => 0.10,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Tritanium'     => 228
            ]
        ];

        $materials['46689'] = [

            'name'          => 'Stable Veldspar',
            'typeID'        => 46689,
            'volume'        => 0.10,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Tritanium'     => 238
            ]
        ];

        $materials['1230'] = [

            'name'          => 'Veldspar',
            'typeID'        => 1230,
            'volume'        => 0.10,
            'portionSize'   => 100,

            'refinedMinerals' =>   [
                'Tritanium'     => 207
            ]
        ];

        $ores = collect($materials);  //Make the nested arrray into a collection

         //foreach ($ores as $ore) {
        //  foreach ($ore['refinedMinerals'] as $index => $refinedMineral) {
        //      echo ('Ore: ' . $index . 'Amount: ' . $refinedMineral . '<br>');
        //  }
        //}

        // Use Array Index Key as TypeID

        foreach ($materials as $typeID => $material) {

            $this->getOreInformation($typeID, json_encode($material));            // Cycle Array and Send TypeID and JSON of Refined Materials to save into DB.

        }
    }

    public function getOreInformation($ore, $ore_json) { // End Point: https://esi.evetech.net/ui/#/Universe/get_universe_types_type_id

        $configuration = Configuration::getInstance();

        $client_id = config('eve.client_id');
        $secret_key = config('eve.secret_key');

        $esi = new Eseye();

        try { 

            $oreDetails = $esi->invoke('get', '/universe/types/{type_id}/', [   
                'type_id' => $ore,
            ]);

            $updateore = Ore::updateOrCreate([
                'type_id'                   => $ore,
            ],[
                'name'                      => $oreDetails->name,
                'description'               => $oreDetails->description,
                'group_id'                  => $oreDetails->group_id,
                'icon_id'                   => $oreDetails->icon_id,
                'portion_size'              => $oreDetails->portion_size,
                'ore_json'                  => $ore_json
            ]);

            $this->info('Ore Added: ' . $oreDetails->name);

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
