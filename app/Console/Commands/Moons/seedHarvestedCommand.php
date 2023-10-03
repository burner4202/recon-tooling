<?php

namespace Vanguard\Console\Commands\Moons;

use Illuminate\Console\Command;

use Vanguard\HarvestedMaterials;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class seedHarvestedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moons:seed:harvested:materials';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed Database with EVE Harvested Products';

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

        ## 2020 March, CCP Fucked it.
        ## Who the fuck modifies refine DNA of ore in a game to fix a fucking rorqual.
        ## https://www.eveonline.com/article/q7v1q4/moon-mineral-distribution-update

    	$materials['22'] = [

    		'name'			=> 'Arkonor',
    		'typeID' 		=> 22,
    		'volume'		=> 16,
    		'portionSize'	=> 100,

    		'refined' =>   [
    			'40' 		=> 320,
    			'36'		=> 2500,
    			'34'		=> 22000
    		]
    	];

    	$materials['17425'] = [

    		'name'			=> 'Crimson Arkonor',
    		'typeID' 		=> 17425,
    		'volume'		=> 16,
    		'portionSize'	=> 100,

    		'refined' =>   [
    			'40' 		=> 336,
    			'36'		=> 2655,
    			'34'		=> 23100
    		]
    	];

    	$materials['46678'] = [

    		'name'			=> 'Flawless Arkonor',
    		'typeID' 		=> 46678,
    		'volume'		=> 16,
    		'portionSize'	=> 100,

    		'refined' =>   [
    			'40' 		=> 386,
    			'36'		=> 2875,
    			'34'		=> 25300
    		]
    	];

    	$materials['17426'] = [

    		'name'			=> 'Prime Arkonor',
    		'typeID' 		=> 17426,
    		'volume'		=> 16,
    		'portionSize'	=> 100,

    		'refined' =>   [
    			'40' 		=> 352,
    			'36'		=> 2750,
    			'34'		=> 24200
    		]
    	];

    	$materials['1223'] = [

    		'name'			=> 'Bisot',
    		'typeID' 		=> 1223,
    		'volume'		=> 16,
    		'portionSize'	=> 100,

    		'refined' =>   [
        		'40' 		=> 100,		  // Megacyte
        		'35'		=> 12000,     // Pyerite
        		'39'		=> 450	 	  // Zydrine
        	]
        ];

        $materials['46676'] = [

        	'name'			=> 'Cubic Bistot',
        	'typeID' 		=> 46676,
        	'volume'		=> 16,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'40' 		=> 115,
        		'35'		=> 13800,
        		'39'		=> 518
        	]
        ];

        $materials['17429'] = [
        	'name'			=> 'Monoclinic Bistot',
        	'typeID' 		=> 17429,
        	'volume'		=> 16,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'40' 		=> 110,
        		'35'		=> 13200,
        		'39'		=> 495
        	]
        ];

        $materials['17428'] = [

        	'name'			=> 'Triclinic Bistot',
        	'typeID' 		=> 17428,
        	'volume'		=> 16,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'40' 		=> 105,
        		'35'		=> 12600,
        		'39'		=> 473
        	]
        ];

        $materials['1225'] = [

        	'name'			=> 'Crokite',
        	'typeID' 		=> 1225,
        	'volume'		=> 16,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'38'		=> 760,
        		'34'		=> 21000,
        		'39'		=> 135
        	]
        ];

        $materials['17433'] = [

        	'name'			=> 'Crystalline Crokite',
        	'typeID' 		=> 17433,
        	'volume'		=> 16,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'38'		=> 836,
        		'34'		=> 23100,
        		'39'		=> 149
        	]
        ];

        $materials['46677'] = [

        	'name'			=> 'Pellucid Crokite',
        	'typeID' 		=> 46677,
        	'volume'		=> 16,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'38'		=> 874,
        		'34'		=> 24150,
        		'39'		=> 155
        	]
        ];

        $materials['17432'] = [

        	'name'			=> 'Sharp Crokite',
        	'typeID' 		=> 17432,
        	'volume'		=> 16,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'38'		=> 798,
        		'34'		=> 22050,
        		'39'		=> 142
        	]
        ];

        $materials['1232'] = [

        	'name'			=> 'Dark Ochre',
        	'typeID' 		=> 1232,
        	'volume'		=> 8,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'37' 		=> 1600,
        		'38'		=> 120,
        		'34'		=> 10000
        	]
        ];

        $materials['46675'] = [

        	'name'			=> 'Jet Ochre',
        	'typeID' 		=> 46675,
        	'volume'		=> 8,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'37' 		=> 1840,
        		'38'		=> 138,
        		'34'		=> 11500
        	]
        ];

        $materials['17437'] = [

        	'name'			=> 'Obsidian Ochre',
        	'typeID' 		=> 17437,
        	'volume'		=> 8,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'37' 		=> 1760,
        		'38'		=> 132,
        		'34'		=> 11000
        	]
        ];

        $materials['17436'] = [

        	'name'			=> 'Onyx Ochre',
        	'typeID' 		=> 17436,
        	'volume'		=> 8,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'37' 		=> 1680,
        		'38'		=> 126,
        		'34'		=> 105000
        	]
        ];

        $materials['46679'] = [

        	'name'			=> 'Brilliant Gneiss',
        	'typeID' 		=> 46679,
        	'volume'		=> 5,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'37' 		=> 345,
        		'36'		=> 2760,
        		'35'		=> 2530
        	]
        ];

        $materials['1229'] = [

        	'name'			=> 'Gneiss',
        	'typeID' 		=> 1229,
        	'volume'		=> 5,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'37' 		=> 300,
        		'36'		=> 2400,
        		'35'		=> 2200
        	]
        ];

        $materials['17865'] = [

        	'name'			=> 'Iridescent Gneiss',
        	'typeID' 		=> 17865,
        	'volume'		=> 5,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'37' 		=> 315,
        		'36'		=> 2520,
        		'35'		=> 2310
        	]
        ];

        $materials['17866'] = [

        	'name'			=> 'Prismatic Gneiss',
        	'typeID' 		=> 17866,
        	'volume'		=> 5,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'37' 		=> 330,
        		'36'		=> 2640,
        		'35'		=> 2420
        	]
        ];

        $materials['17441'] = [

        	'name'			=> 'Glazed Hedbergite',
        	'typeID' 		=> 17441,
        	'volume'		=> 3,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'37' 		=> 220,
        		'38'		=> 110,
        		'35'		=> 1100,
        		'39'		=> 21
        	]
        ];

        $materials['21'] = [

        	'name'			=> 'Hedbergite',
        	'typeID' 		=> 21,
        	'volume'		=> 3,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'37' 		=> 200,
        		'38'		=> 100,
        		'35'		=> 1000,
        		'39'		=> 19
        	]
        ];

        $materials['46680'] = [

        	'name'			=> 'Lustrous Hedbergite',
        	'typeID' 		=> 46680,
        	'volume'		=> 3,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'37' 		=> 230,
        		'38'		=> 115,
        		'35'		=> 1150,
        		'39'		=> 22
        	]
        ];

        $materials['17440'] = [

        	'name'			=> 'Vitric Hedbergite',
        	'typeID' 		=> 17440,
        	'volume'		=> 3,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'37' 		=> 210,
        		'38'		=> 105,
        		'35'		=> 1050,
        		'39'		=> 20
        	]
        ];

        $materials['1231'] = [

        	'name'			=> 'Hemorphite',
        	'typeID' 		=> 1231,
        	'volume'		=> 3,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'37' 		=> 100,
        		'38'		=> 120,
        		'34'		=> 2200,
        		'39'		=> 15
        	]
        ];

        $materials['17445'] = [

        	'name'			=> 'Radiant Hemorphite',
        	'typeID' 		=> 17445,
        	'volume'		=> 3,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'37' 		=> 110,
        		'38'		=> 132,
        		'34'		=> 2420,
        		'39'		=> 17
        	]
        ];

        $materials['46681'] = [

        	'name'			=> 'Scintillating Hemorphite',
        	'typeID' 		=> 46681,
        	'volume'		=> 3,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'37' 		=> 115,
        		'38'		=> 138,
        		'34'		=> 2530,
        		'39'		=> 17
        	]
        ];

        $materials['17444'] = [

        	'name'			=> 'Vivid Hemorphite',
        	'typeID' 		=> 17444,
        	'volume'		=> 3,
        	'portionSize'	=> 100,

        	'refined' =>  [
        		'37' 		=> 105,
        		'38'		=> 126,
        		'34'		=> 2310,
        		'39'		=> 16
        	]
        ];

        $materials['46682'] = [

        	'name'			=> 'Immaculate Jaspet',
        	'typeID' 		=> 46682,
        	'volume'		=> 2,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'36'		=> 403,
        		'38'		=> 86,
        		'39'		=> 9
        	]
        ];

        $materials['1226'] = [

        	'name'			=> 'Jaspet',
        	'typeID' 		=> 1226,
        	'volume'		=> 2,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'36'		=> 350,
        		'38'		=> 75,
        		'39'		=> 8
        	]
        ];

        $materials['17449'] = [

        	'name'			=> 'Pristine Jaspet',
        	'typeID' 		=> 17449,
        	'volume'		=> 2,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'36'		=> 385,
        		'38'		=> 83,
        		'39'		=> 9
        	]
        ];

        $materials['17448'] = [


        	'name'			=> 'Pure Jaspet',
        	'typeID' 		=> 17448,
        	'volume'		=> 2,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'36'		=> 368,
        		'38'		=> 79,
        		'39'		=> 8
        	]
        ];

        $materials['17453'] = [

        	'name'			=> 'Fiery Kernite',
        	'typeID' 		=> 17453,
        	'volume'		=> 1.2,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'37' 		=> 148,
        		'36'		=> 294,
        		'34'		=> 148
        	]
        ];

        $materials['20'] = [

        	'name'			=> 'Kernite',
        	'typeID' 		=> 20,
        	'volume'		=> 1.2,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'37' 		=> 134,
        		'36'		=> 267,
        		'34'		=> 134
        	]
        ];


        $materials['17452'] = [


        	'name'			=> 'Luminous Kernite',
        	'typeID' 		=> 17452,
        	'volume'		=> 1.2,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'37' 		=> 141,
        		'36'		=> 281,
        		'34'		=> 141
        	]
        ];

        $materials['46683'] = [


        	'name'			=> 'Resplendant Kernite',
        	'typeID' 		=> 46683,
        	'volume'		=> 1.2,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'37' 		=> 154,
        		'36'		=> 307,
        		'34'		=> 154
        	]
        ];

        $materials['17869'] = [


        	'name'			=> 'Magma Mercoxit',
        	'typeID' 		=> 17869,
        	'volume'		=> 40,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'11399' 		=> 315,
        	]
        ];

        $materials['11396'] = [


        	'name'			=> 'Mercoxit',
        	'typeID' 		=> 11396,
        	'volume'		=> 40,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'11399' 		=> 300,
        	]
        ];

        $materials['17870'] = [


        	'name'			=> 'Viteous Mercoxit',
        	'typeID' 		=> 17870,
        	'volume'		=> 40,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'11399' 		=> 330,
        	]
        ];

        $materials['17868'] = [


        	'name'			=> 'Golden Omber',
        	'typeID' 		=> 17868,
        	'volume'		=> 0.6,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'37' 		=> 94,
        		'35'		=> 110,
        		'34'		=> 880
        	]
        ];

        $materials['1227'] = [


        	'name'			=> 'Omber',
        	'typeID' 		=> 1227,
        	'volume'		=> 0.6,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'37' 		=> 85,
        		'35'		=> 100,
        		'34'		=> 800
        	]
        ];

        $materials['46684'] = [


        	'name'			=> 'Platinoid Omber',
        	'typeID' 		=> 46684,
        	'volume'		=> 0.6,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'37' 		=> 98,
        		'35'		=> 115,
        		'34'		=> 920
        	]
        ];

        $materials['17867'] = [


        	'name'			=> 'Silvery Omber',
        	'typeID' 		=> 17867,
        	'volume'		=> 0.6,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'37' 		=> 90,
        		'35'		=> 105,
        		'34'		=> 840
        	]
        ];

        $materials['17455'] = [


        	'name'			=> 'Azure Plagioclase',
        	'typeID' 		=> 17455,
        	'volume'		=> 0.35,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'36'		=> 113,
        		'35'		=> 224,
        		'34'		=> 113
        	]
        ];

        $materials['18'] = [


        	'name'			=> 'Plagioclase',
        	'typeID' 		=> 18,
        	'volume'		=> 0.35,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'36'		=> 107,
        		'35'		=> 213,
        		'34'		=> 107
        	]
        ];

        $materials['17456'] = [

        	'name'			=> 'Rich Plagioclase',
        	'typeID' 		=> 17456,
        	'volume'		=> 0.35,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'36'		=> 118,
        		'35'		=> 235,
        		'34'		=> 118
        	]
        ];

        $materials['46685'] = [


        	'name'			=> 'Sparkling Plagioclase',
        	'typeID' 		=> 46685,
        	'volume'		=> 0.35,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'36'		=> 123,
        		'35'		=> 245,
        		'34'		=> 123
        	]
        ];

        $materials['46686'] = [


        	'name'			=> 'Opulent Pyroxeres',
        	'typeID' 		=> 46686,
        	'volume'		=> 0.30,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'36'		=> 58,
        		'38'		=> 6,
        		'35'		=> 29,
        		'34'		=> 404
        	]
        ];


        $materials['1224'] = [


        	'name'			=> 'Pyroxeres',
        	'typeID' 		=> 1224,
        	'volume'		=> 0.30,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'36'		=> 50,
        		'38'		=> 5,
        		'35'		=> 25,
        		'34'		=> 351
        	]
        ];

        $materials['17459'] = [


        	'name'			=> 'Solid Pyroxeres',
        	'typeID' 		=> 17459,
        	'volume'		=> 0.30,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'36'		=> 53,
        		'38'		=> 5,
        		'35'		=> 26,
        		'34'		=> 369
        	]
        ];

        $materials['17460'] = [


        	'name'			=> 'Viscous Pyroxeres',
        	'typeID' 		=> 17460,
        	'volume'		=> 0.30,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'36'		=> 55,
        		'38'		=> 5,
        		'35'		=> 27,
        		'34'		=> 387
        	]
        ];

        $materials['17463'] = [


        	'name'			=> 'Condensed Scordite',
        	'typeID' 		=> 17463,
        	'volume'		=> 0.15,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'35'		=> 182,
        		'34'		=> 384
        	]
        ];

        $materials['46687'] = [


        	'name'			=> 'Glossy Scordite',
        	'typeID' 		=> 46687,
        	'volume'		=> 0.15,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'35'		=> 199,
        		'34'		=> 398
        	]
        ];

        $materials['17464'] = [


        	'name'			=> 'Massive Scordite',
        	'typeID' 		=> 17464,
        	'volume'		=> 0.15,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'35'		=> 95,
        		'34'		=> 190
        	]
        ];

        $materials['1228'] = [


        	'name'			=> 'Scordite',
        	'typeID' 		=> 1228,
        	'volume'		=> 0.15,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'35'		=> 190,
        		'34'		=> 381
        	]
        ];

        $materials['17466'] = [


        	'name'			=> 'Bright Spodumain',
        	'typeID' 		=> 17466,
        	'volume'		=> 16,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'37' 		=> 473,
        		'36'		=> 2205,
        		'35'		=> 12653,
        		'34'		=> 58800
        	]
        ];

        $materials['46688'] = [


        	'name'			=> 'Dazzling Spodumain',
        	'typeID' 		=> 46688,
        	'volume'		=> 16,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'37' 		=> 518,
        		'36'		=> 2415,
        		'35'		=> 13858,
        		'34'		=> 64400
        	]
        ];

        $materials['17467'] = [

        	'name'			=> 'Gleaming Spodumain',
        	'typeID' 		=> 17467,
        	'volume'		=> 16,
        	'portionSize'	=> 100,

        	'refined' =>   [
        		'37' 		=> 495,
        		'36'		=> 2310,
        		'35'		=> 13255,
        		'34'		=> 61600
        	]
        ];

        $materials['19'] = [

        	'name'          => 'Spodumain',
        	'typeID'        => 19,
        	'volume'        => 16,
        	'portionSize'   => 100,

        	'refined' =>   [
        		'37' 		=> 450,
        		'36'		=> 2100,
        		'35'		=> 12050,
        		'34'		=> 56000
        	]
        ];


        $materials['17470'] = [


        	'name'          => 'Concentrated Veldspar',
        	'typeID'        => 17470,
        	'volume'        => 0.10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		'34'     => 436,
        	]
        ];

        $materials['17471'] = [


        	'name'          => 'Dense Veldspar',
        	'typeID'        => 17471,
        	'volume'        => 0.10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		'34'     => 457
        	]
        ];

        $materials['46689'] = [

        	'name'          => 'Stable Veldspar',
        	'typeID'        => 46689,
        	'volume'        => 0.10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		'34'     => 477
        	]
        ];

        $materials['1230'] = [

        	'name'          => 'Veldspar',
        	'typeID'        => 1230,
        	'volume'        => 0.10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		'34'     => 415
        	]
        ];

         // Ice Products

        $materials['16264'] = [

        	'name'          => 'Blue Ice',
        	'typeID'        => 16264,
        	'volume'        => 1000,
        	'portionSize'   => 1,

        	'refined' =>   [
        		'16272'             => 69,
        		'16273'            => 35,
        		'16275'    => 1,
        		'17887'         => 414,
        	]
        ];

        $materials['16262'] = [

        	'name'            => 'Clear Icicle',
        	'typeID'          => 16262,
        	'volume'          => 1000,
        	'portionSize'     => 1,

        	'refined' =>   [
        		'16272'              => 69,
        		'16273'             => 35,
        		'16275'    => 1,
        		'16274'          => 414,
        	]
        ];

        $materials['16267'] = [

        	'name'            => 'Dark Glitter',
        	'typeID'      => 16267,
        	'volume'      => 1000,
        	'portionSize' => 1,

        	'refined' => [
        		'16272'              => 691,
        		'16273'             => 1381,
        		'16275'   => 69,
        	]
        ];

        $materials['17978'] = [

        	'name'            => 'Enriched Clear Icicle',
        	'typeID'      => 17978,
        	'volume'      => 1000,
        	'portionSize' => 1,

        	'refined' =>   [
        		'16272'            => 104,
        		'16273'           => 55,
        		'16274'        => 483,
        		'16275'  => 1,
        	]
        ];

        $materials['16268'] = [

        	'name'            => 'Gelidus',
        	'typeID'      => 16268,
        	'volume'      => 1000,
        	'portionSize' => 1,

        	'refined' =>   [
        		'16272'            => 345,
        		'16273'           => 691,
        		'16275'   => 104,
        	]
        ];

        $materials['16263'] = [

        	'name'            => 'Glacial Mass',
        	'typeID'      => 16263,
        	'volume'      => 1000,
        	'portionSize' => 1,

        	'refined' =>   [
        		'16272'            => 69,
        		'16273'           => 35,
        		'16275'  => 1,
        		'17889'      => 414,
        	]
        ];


        $materials['16266'] = [

        	'name'            => 'Glare Crust',
        	'typeID'      => 16266,
        	'volume'      => 1000,
        	'portionSize' => 1,

        	'refined' =>   [
        		'16272'            => 1381,
        		'16273'           => 691,
        		'16275'   => 35,
        	]
        ];

        $materials['16269'] = [

        	'name'            => 'Krystallos',
        	'typeID'      => 16269,
        	'volume'      => 1000,
        	'portionSize' => 1,

        	'refined' =>   [
        		'16272'            => 173,
        		'16273'           => 381,
        		'16275'   => 173,
        	]
        ];
        $materials['17976'] = [

        	'name'            => 'Pristine White Glaze',
        	'typeID'      => 17976,
        	'volume'      => 1000,
        	'portionSize' => 1,

        	'refined' =>   [
        		'16272'            => 104,
        		'16273'           => 55,
        		'16275'  => 1,
        		'17888'      => 483,
        	]
        ];
        $materials['17977'] = [

        	'name'            => 'Smooth Glacial Mass',
        	'typeID'      => 17977,
        	'volume'      => 1000,
        	'portionSize' => 1,

        	'refined' =>   [
        		'16272'            => 104,
        		'16273'           => 55,
        		'16275'  => 1,
        		'17889'      => 483,
        	]
        ];
        $materials['17975'] = [

        	'name'            => 'Thick Blue Ice',
        	'typeID'      => 17975,
        	'volume'      => 1000,
        	'portionSize' => 1,

        	'refined' =>   [
        		'16272'            => 104,
        		'16273'           => 55,
        		'16275'  => 1,
        		'17887'        => 483,
        	]
        ];
        $materials['16265'] = [

        	'name'            => 'White Glaze',
        	'typeID'      => 16265,
        	'volume'      => 1000,
        	'portionSize' => 1,

        	'refined' =>   [
        		'16272'            => 69,
        		'16273'           => 35,
        		'16275'  => 1,
        		'17888'      => 414,
        	]
        ];

// Common Moon Ores

        $materials['45494'] = [

        	'name'          => 'Cobaltite',
        	'typeID'        => 45494,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'34'             => 7500,
        		#'35'               => 10000,
        		#'36'              => 500,
        		'16640'                => 40,
        	]
        ];

        $materials['46288'] = [

        	'name'          => 'Copious Cobaltite',
        	'typeID'        => 46288,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'34'             => 8625,
        		#'35'               => 11500,
        		#'36'              => 575,
        		'16640'                => 46,
        	]
        ];

        $materials['46290'] = [

        	'name'          => 'Copious Euxenite',
        	'typeID'        => 46290,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'34'             => 11500,
        		#'35'               => 8625,
        		#'36'              => 575,
        		'16639'              => 46,
        	]
        ];

        $materials['46294'] = [

        	'name'          => 'Copious Scheelite',
        	'typeID'        => 46294,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'34'             => 14375,
        		#'35'               => 5750,
        		#'36'              => 575,
        		'16637'              => 46,
        	]
        ];

        $materials['46292'] = [

        	'name'          => 'Copious Titanite',
        	'typeID'        => 46292,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'34'             => 17250,
        		#'35'               => 2875,
        		#'36'              => 575,
        		'16638'              => 46,
        	]
        ];

        $materials['45495'] = [

        	'name'          => 'Euxenite',
        	'typeID'        => 45495,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'34'             => 10000,
        		#'35'               => 7500,
        		#'36'              => 500,
        		'16639'              => 40,
        	]
        ];

        $materials['45497'] = [

        	'name'          => 'Scheelite',
        	'typeID'        => 45497,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'34'             => 12500,
        		#'35'               => 5000,
        		#'36'              => 500,
        		'16637'              => 40,
        	]
        ];

        $materials['45496'] = [

        	'name'          => 'Titanite',
        	'typeID'        => 45496,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'34'             => 12500,
        		#'35'               => 2500,
        		#'36'              => 500,
        		'16638'              => 40,
        	]
        ];

        $materials['46289'] = [

        	'name'          => 'Twinkling Cobaltite',
        	'typeID'        => 46289,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'34'             => 15000,
        		#'35'               => 20000,
        		#'36'              => 1000,
        		'16640'                => 80,
        	]
        ];

        $materials['46291'] = [

        	'name'          => 'Twinkling Euxenite',
        	'typeID'        => 46291,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'34'             => 20000,
        		#'35'               => 15000,
        		#'36'              => 1000,
        		'16639'              => 80,
        	]
        ];

        $materials['46295'] = [

        	'name'          => 'Twinkling Scheelite',
        	'typeID'        => 46295,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'34'             => 25000,
        		#'35'               => 10000,
        		#'36'              => 1000,
        		'16637'              => 80,
        	]
        ];

        $materials['46293'] = [

        	'name'          => 'Twinkling Titanite',
        	'typeID'        => 46293,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'34'             => 30000,
        		#'35'               => 5000,
        		#'36'              => 1000,
        		'16638'              => 80,
        	]
        ];

// Uncommon Moon Ores

        $materials['45501'] = [

        	'name'          => 'Chromite',
        	'typeID'        => 45501,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'35'               => 5000,
        		#'36'              => 1250,
        		#'37'                => 750,
        		#'38'               => 50,
        		'16633'          => 10,
        		'16641'              => 40
        	]
        ];

        $materials['46302'] = [

        	'name'          => 'Lavish Chromite',
        	'typeID'        => 46302,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'35'               => 5750,
        		#'36'              => 1438,
        		#'37'                => 863,
        		#'38'               => 58,
        		'16633'          => 12,
        		'16641'              => 46
        	]
        ];

        $materials['46296'] = [

        	'name'          => 'Lavish Otavite',
        	'typeID'        => 46296,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'34'                   => 5750,
        		#'36'                    => 1725,
        		#'37'                      => 575,
        		#'38'                     => 58,
        		'16634'          => 12,
        		'16643'                     => 46
        	]
        ];

        $materials['46298'] = [

        	'name'          => 'Lavish Sperrylite',
        	'typeID'        => 46298,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'34'                   => 5750,
        		#'36'                    => 1150,
        		#'37'                      => 1150,
        		#'39'                     => 58,
        		'16635'          => 12,
        		'16644'                    => 46
        	]
        ];

        $materials['46300'] = [

        	'name'          => 'Lavish Vanadinite',
        	'typeID'        => 46300,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'35'                     => 5750,
        		#'36'                    => 863,
        		#'37'                      => 1438,
        		#'39'                     => 58,
        		'16636'                   => 12,
        		'16642'                    => 46
        	]
        ];

        $materials['45498'] = [

        	'name'          => 'Otavite',
        	'typeID'        => 45498,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'34'                   => 5000,
        		#'36'                    => 1500,
        		#'37'                      => 500,
        		#'38'                     => 50,
        		'16634'          => 10,
        		'16643'                     => 40
        	]
        ];

        $materials['46303'] = [

        	'name'          => 'Shimmering Chromite',
        	'typeID'        => 46303,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'35'               => 10000,
        		#'36'              => 2500,
        		#'37'                => 1500,
        		#'38'               => 100,
        		'16633'          => 20,
        		'16641'              => 80
        	]
        ];

        $materials['46297'] = [

        	'name'          => 'Shimmering Otavite',
        	'typeID'        => 46297,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'34'                   => 10000,
        		#'36'                    => 3000,
        		#'37'                      => 1000,
        		#'38'                     => 100,
        		'16634'          => 20,
        		'16643'                     => 80
        	]
        ];

        $materials['46299'] = [

        	'name'          => 'Shimmering Sperrylite',
        	'typeID'        => 46299,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'34'                   => 10000,
        		#'36'                    => 2000,
        		#'37'                      => 2000,
        		#'39'                     => 100,
        		'16635'          => 20,
        		'16644'                    => 80
        	]
        ];

        $materials['46301'] = [

        	'name'          => 'Shimmering Vanadinite',
        	'typeID'        => 46301,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'35'                     => 10000,
        		#'36'                    => 1500,
        		#'37'                      => 2500,
        		#'39'                     => 100,
        		'16636'                   => 20,
        		'16642'                    => 80
        	]
        ];

        $materials['45499'] = [

        	'name'          => 'Sperrylite',
        	'typeID'        => 45499,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'34'                   => 5000,
        		#'36'                    => 1000,
        		#'37'                      => 1000,
        		#'39'                     => 50,
        		'16635'          => 10,
        		'16644'                    => 40
        	]
        ];

        $materials['45500'] = [

        	'name'          => 'Vanadinite',
        	'typeID'        => 45500,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'35'                     => 5000,
        		#'36'                    => 1000,
        		#'37'                      => 1250,
        		#'39'                     => 50,
        		'16636'                   => 10,
        		'16642'                    => 40
        	]
        ];

        $materials['45492'] = [

        	'name'          => 'Bitumens',
        	'typeID'        => 45492,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'34'                   => 6000,
        		'35'                     => 6000,
        		'36'                    => 400,
        		'16633'                => 65,
        	]
        ];

        $materials['46284'] = [

        	'name'          => 'Brimful Bitumens',
        	'typeID'        => 46284,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'34'                   => 6900,
        		'35'                     => 6900,
        		'36'                    => 460,
        		'16633'                => 75,
        	]
        ];

        $materials['46286'] = [

        	'name'          => 'Brimful Coesite',
        	'typeID'        => 46286,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'34'                   => 11500,
        		'35'                     => 2300,
        		'36'                    => 460,
        		'16636'                   => 75,
        	]
        ];

        $materials['46282'] = [

        	'name'          => 'Brimful Sylvite',
        	'typeID'        => 46282,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'34'                   => 9200,
        		'35'                     => 4600,
        		'36'                    => 460,
        		'16635'          => 75,
        	]
        ];

        $materials['46280'] = [

        	'name'          => 'Brimful Zeolites',
        	'typeID'        => 46280,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'34'                   => 4600,
        		'35'                     => 9200,
        		'36'                    => 460,
        		'16634'          => 75,
        	]
        ];

        $materials['45493'] = [

        	'name'          => 'Coesite',
        	'typeID'        => 45493,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'34'                   => 10000,
        		'35'                     => 2000,
        		'36'                    => 400,
        		'16636'                   => 65,
        	]
        ];

        $materials['46285'] = [

        	'name'          => 'Glistening Bitumens',
        	'typeID'        => 46285,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'34'                   => 12000,
        		'35'                     => 12000,
        		'36'                    => 800,
        		'16633'                => 130,
        	]
        ];

        $materials['46287'] = [

        	'name'          => 'Glistening Coesite',
        	'typeID'        => 46287,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'34'                   => 20000,
        		'35'                     => 4000,
        		'36'                    => 800,
        		'16636'                   => 130,
        	]
        ];

        $materials['46283'] = [

        	'name'          => 'Glistening Sylvite',
        	'typeID'        => 46283,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'34'                   => 16000,
        		'35'                     => 8000,
        		'36'                    => 800,
        		'16635'          => 130,
        	]
        ];

        $materials['46281'] = [

        	'name'          => 'Glistening Zeolites',
        	'typeID'        => 46281,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'34'                   => 8000,
        		'35'                     => 16000,
        		'36'                    => 800,
        		'16634'          => 130,
        	]
        ];

        $materials['45491'] = [

        	'name'          => 'Sylvite',
        	'typeID'        => 45491,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'34'                   => 8000,
        		'35'                     => 4000,
        		'36'                    => 400,
        		'16635'          => 65,
        	]
        ];

        $materials['45490'] = [

        	'name'          => 'Zeolites',
        	'typeID'        => 45490,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'34'                   => 4000,
        		'35'                     => 8000,
        		'36'                    => 400,
        		'16634'          => 65,
        	]
        ];

        $materials['45502'] = [

        	'name'          => 'Carnotite',
        	'typeID'        => 45502,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'36'                    => 1000,
        		#'37'                      => 1250,
        		#'39'                     => 50,
        		'16634'          => 15,
        		'16640'                      => 10,
        		'16649'                  => 50,
        	]
        ];

        $materials['45506'] = [

        	'name'          => 'Cinnabar',
        	'typeID'        => 45506,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'36'                    => 1500,
        		#'37'                      => 750,
        		#'40'                    => 50,
        		'16635'          => 15,
        		'16637'                    => 10,
        		'16646'                     => 50,
        	]
        ];

        $materials['46305'] = [

        	'name'          => 'Glowing Carnotite',
        	'typeID'        => 46305,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'36'                    => 2000,
        		#'37'                      => 2500,
        		#'39'                     => 100,
        		'16634'          => 30,
        		'16640'                      => 20,
        		'16649'                  => 100,
        	]
        ];

        $materials['46311'] = [

        	'name'          => 'Glowing Cinnabar',
        	'typeID'        => 46311,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'36'                    => 3000,
        		#'37'                      => 1500,
        		#'40'                    => 100,
        		'16635'          => 30,
        		'16637'                    => 20,
        		'16646'                     => 100,
        	]
        ];

        $materials['46309'] = [

        	'name'          => 'Glowing Pollucite',
        	'typeID'        => 46309,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'36'                    => 2500,
        		#'37'                      => 2000,
        		#'39'                     => 100,
        		'16633'                => 30,
        		'16639'                    => 20,
        		'16647'                     => 100,
        	]
        ];

        $materials['46307'] = [

        	'name'          => 'Glowing Zircon',
        	'typeID'        => 46307,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'36'                    => 3500,
        		#'37'                      => 1000,
        		#'40'                    => 100,
        		'16636'                   => 30,
        		'16638'                    => 20,
        		'16648'                     => 100,
        	]
        ];

        $materials['45504'] = [

        	'name'          => 'Pollucite',
        	'typeID'        => 45504,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'36'                    => 1250,
        		#'37'                      => 1000,
        		#'39'                     => 50,
        		'16633'                => 15,
        		'16639'                    => 10,
        		'16647'                     => 50,
        	]
        ];

        $materials['46304'] = [

        	'name'          => 'Replete Carnotite',
        	'typeID'        => 46304,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'36'                    => 1150,
        		#'37'                      => 1438,
        		#'39'                     => 58,
        		'16634'          => 17,
        		'16640'                      => 12,
        		'16649'                  => 50,
        	]
        ];

        $materials['46310'] = [

        	'name'          => 'Replete Cinnabar',
        	'typeID'        => 46310,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'36'                    => 1725,
        		#'37'                      => 863,
        		#'40'                    => 58,
        		'16635'          => 17,
        		'16637'                    => 12,
        		'16646'                     => 58,
        	]
        ];

        $materials['46308'] = [

        	'name'          => 'Replete Pollucite',
        	'typeID'        => 46308,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'36'                    => 1438,
        		#'37'                      => 1150,
        		#'39'                     => 58,
        		'16633'                => 17,
        		'16639'                    => 12,
        		'16647'                     => 58,
        	]
        ];

        $materials['46306'] = [

        	'name'          => 'Replete Zircon',
        	'typeID'        => 46306,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'36'                    => 2013,
        		#'37'                      => 575,
        		#'40'                    => 58,
        		'16636'                   => 17,
        		'16638'                    => 12,
        		'16648'                     => 58,
        	]
        ];

        $materials['45503'] = [

        	'name'          => 'Zircon',
        	'typeID'        => 45503,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'36'                    => 1750,
        		#'37'                      => 500,
        		#'40'                    => 50,
        		'16636'                   => 15,
        		'16638'                    => 10,
        		'16648'                     => 50,
        	]
        ];

        $materials['46316'] = [

        	'name'          => 'Bountiful Loparite',
        	'typeID'        => 46316,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'38'                     => 115,
        		#'39'                     => 230,
        		#'40'                    => 58,
        		'16633'                => 23,
        		'16639'                    => 23,
        		'16644'                    => 12,
        		'16652'                  => 25,
        	]
        ];

        $materials['46314'] = [

        	'name'          => 'Bountiful Monazite',
        	'typeID'        => 46314,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'38'                     => 58,
        		#'39'                     => 173,
        		#'40'                    => 173,
        		'16635'          => 23,
        		'16637'                    => 23,
        		'16641'                    => 12,
        		'16651'                   => 25,
        	]
        ];

        $materials['46312'] = [

        	'name'          => 'Bountiful Xenotime',
        	'typeID'        => 46312,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'38'                     => 230,
        		#'39'                     => 115,
        		#'40'                    => 58,
        		'16634'          => 23,
        		'16640'                      => 23,
        		'16642'                    => 12,
        		'16650'                  => 25,
        	]
        ];

        $materials['46318'] = [

        	'name'          => 'Bountiful Ytterbite',
        	'typeID'        => 46318,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'38'                     => 58,
        		#'39'                     => 115,
        		#'40'                    => 230,
        		'16636'                   => 23,
        		'16638'                    => 23,
        		'16643'                     => 12,
        		'16653'                     => 25,
        	]
        ];

        $materials['45512'] = [

        	'name'          => 'Loparite',
        	'typeID'        => 45512,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'38'                     => 100,
        		#'39'                     => 200,
        		#'40'                    => 50,
        		'16633'                => 20,
        		'16639'                    => 20,
        		'16644'                    => 10,
        		'16652'                  => 22,
        	]
        ];

        $materials['45511'] = [

        	'name'          => 'Monazite',
        	'typeID'        => 45511,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'38'                     => 50,
        		#'39'                     => 150,
        		#'40'                    => 150,
        		'16635'          => 20,
        		'16637'                    => 20,
        		'16641'                    => 10,
        		'16651'                   => 22,
        	]
        ];

        $materials['46317'] = [

        	'name'          => 'Shining Loparite',
        	'typeID'        => 46317,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'38'                     => 200,
        		#'39'                     => 400,
        		#'40'                    => 100,
        		'16633'                => 40,
        		'16639'                    => 40,
        		'16644'                    => 20,
        		'16652'                  => 44,
        	]
        ];

        $materials['46315'] = [

        	'name'          => 'Shining Monazite',
        	'typeID'        => 46315,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'38'                     => 100,
        		#'39'                     => 300,
        		#'40'                    => 300,
        		'16635'          => 40,
        		'16637'                    => 40,
        		'16641'                    => 20,
        		'16651'                   => 44,
        	]
        ];

        $materials['46313'] = [

        	'name'          => 'Shining Xenotime',
        	'typeID'        => 46313,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'38'                     => 400,
        		#'39'                     => 200,
        		#'40'                    => 100,
        		'16634'          => 40,
        		'16640'                      => 40,
        		'16642'                    => 20,
        		'16650'                  => 44,
        	]
        ];

        $materials['46319'] = [

        	'name'          => 'Shining Ytterbite',
        	'typeID'        => 46319,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'38'                     => 100,
        		#'39'                     => 200,
        		#'40'                    => 400,
        		'16636'                   => 40,
        		'16638'                    => 40,
        		'16643'                     => 20,
        		'16653'                     => 44,
        	]
        ];

        $materials['45510'] = [

        	'name'          => 'Xenotime',
        	'typeID'        => 45510,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'38'                     => 200,
        		#'39'                     => 100,
        		#'40'                    => 50,
        		'16634'          => 20,
        		'16640'                      => 20,
        		'16642'                    => 10,
        		'16650'                  => 22,
        	]
        ];

        $materials['45513'] = [

        	'name'          => 'Ytterbite',
        	'typeID'        => 45513,
        	'volume'        => 10,
        	'portionSize'   => 100,

        	'refined' =>   [
        		#'38'                     => 50,
        		#'39'                     => 100,
        		#'40'                    => 200,
        		'16636'                   => 20,
        		'16638'                    => 20,
        		'16643'                     => 10,
        		'16653'                     => 22,
        	]
        ];




    	$collection = collect($materials);  //Make the nested arrray into a collection

    	 //foreach ($ores as $ore) {
    	//	foreach ($ore['refined'] as $index => $refinedMineral) {
    	//		echo ('Ore: ' . $index . 'Amount: ' . $refinedMineral . '<br>');
    	//	}
    	//}

    	// Use Array Index Key as TypeID

    	foreach ($collection as $id => $material) {

    		$this->getInformation($id, json_encode($material));			  // Cycle Array and Send TypeID and JSON of Refined Materials to save into DB.

    	}
    }

    public function getInformation($id, $json) { // End Point: https://esi.evetech.net/ui/#/Universe/get_universe_types_type_id

    	$configuration = Configuration::getInstance();

    	$client_id = config('eve.client_id');
    	$secret_key = config('eve.secret_key');

    	$esi = new Eseye();

    	try { 

    		$data = $esi->invoke('get', '/universe/types/{type_id}/', [   
    			'type_id' => $id,
    		]);

    		$updateore = HarvestedMaterials::updateOrCreate([
    			'type_id'      				=> $id,
    		],[
    			'name'  					=> $data->name,
    			'description'  				=> $data->description,
    			'group_id'  				=> $data->group_id,
    			'icon_id'  					=> $data->icon_id,
    			'portion_size'  			=> $data->portion_size,
    			'json'  					=> $json
    		]);

    		$this->info('Material Added: ' . $data->name);

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
