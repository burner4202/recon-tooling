<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;

use Datetime;

use Auth;
use Log;
use Carbon\Carbon;
use Vanguard\User;
use Vanguard\UpwellRigs;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class seedRigManufacture extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:seedUpwellRigs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populates Rig Database and Industry Materials';

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
        // Compose what it costs to build the rigs

    	$rig['43709'] = [

    		'name'			=> 'Standup L-Set Advanced Medium Ship Manufacturing Efficiency I',
    		'typeID' 		=> 43709,
    		'install_price'  => '12135008.75',
    		'materials' =>   [
    			'25591'=>10154,
    			'25590'=>6092,
    			'25594'=>10154,
    			'25592'=>10154,
    			'25604'=>10154,
    			'25593'=>10154,
    			'25601'=>9138,
    			'25597'=>10154,
    			'25598'=>3046,
    			'25599'=>10154,
    		]
    	];

    	$rig['37160'] = [

    		'name'			=> 'Standup M-Set Equipment Manufacturing Time Efficiency I',
    		'typeID' 		=> 37160,
    		'install_price'  => '1265295.04',
    		'materials' =>   [
    			'25591'=>1354,
    			'25590'=>813,
    			'25594'=>1354,
    			'25592'=>1354,
    			'25604'=>1354,
    			'25593'=>1354,
    			'25601'=>1219,
    			'25597'=>1354,
    			'25599'=>1354,
    		]
    	];

    	$rig['43709'] = [

    		'name'			=> 'Standup M-Set Equipment Manufacturing Time Efficiency II',
    		'typeID' 		=> 43709,
    		'install_price'  => '13773947.76',
    		'materials' =>   [
    			'25612'=>1016,
    			'25613'=>170,
    			'25610'=>170,
    			'25611'=>1016,
    			'25617'=>14,
    			'25618'=>1016,
    			'25609'=>17,
    			'25616'=>847,
    			'25623'=>170,
    			'25620'=>24,
    			'11475'=>1,
    		]
    	];

    	$rig['43920'] = [

    		'name'			=> 'Standup M-Set Equipment Manufacturing Material Efficiency I',
    		'typeID' 		=> 43920,
    		'install_price'  => '10179364.80',
    		'materials' =>   [
    			'25591'=>1354,
    			'25590'=>813,
    			'25594'=>1354,
    			'25592'=>1354,
    			'25604'=>1354,
    			'25593'=>1354,
    			'25601'=>1219,
    			'25597'=>1354,
    			'25599'=>1354,
    		]
    	];

    	$rig['43921'] = [

    		'name'			=> 'Standup M-Set Equipment Manufacturing Material Efficiency II',
    		'typeID' 		=> 43921,
    		'install_price'  => '13773947.76',
    		'materials' =>   [
    			'25612'=>1016,
    			'25613'=>170,
    			'25610'=>170,
    			'25611'=>1016,
    			'25617'=>14,
    			'25618'=>1016,
    			'25609'=>17,
    			'25616'=>847,
    			'25623'=>170,
    			'25620'=>24,
    			'11475'=>1,
    		]
    	];

    	$rig['37170'] = [

    		'name'			=> 'Standup L-Set Equipment Manufacturing Efficiency I',
    		'typeID' 		=> 37170,
    		'install_price'  => '12135008.75',
    		'materials' =>   [
    			'25591'=>10154,
    			'25590'=>6092,
    			'25594'=>10154,
    			'25592'=>10154,
    			'25604'=>10154,
    			'25593'=>10154,
    			'25601'=>9183,
    			'25597'=>10154,
    			'25598'=>3046,
    			'25599'=>10154,
    		]
    	];

    	$rig['37171'] = [

    		'name'			=> 'Standup L-Set Equipment Manufacturing Efficiency II',
    		'typeID' 		=> 37171,
    		'install_price'  => '137738800.19',
    		'materials' =>   [
    			'25612'=>10154,
    			'25613'=>1693,
    			'25610'=>1693,
    			'25611'=>10154,
    			'25617'=>136,
    			'25618'=>10154,
    			'25609'=>170,
    			'25616'=>8461,
    			'25623'=>1693,
    			'25620'=>237,
    			'11475'=>1,
    		]
    	];

    	$rig['43712'] = [

    		'name'			=> 'Standup L-Set Drone and Fighter Manufacturing Efficiency I',
    		'typeID' 		=> 43712,
    		'install_price'  => '12135008.75',
    		'materials' =>   [
    			'25591'=>10154,
    			'25590'=>6092,
    			'25594'=>10154,
    			'25592'=>10154,
    			'25604'=>10154,
    			'25593'=>10154,
    			'25601'=>9183,
    			'25597'=>10154,
    			'25598'=>3046,
    			'25599'=>10154,
    		]
    	];

    	$rig['43713'] = [

    		'name'			=> 'Standup L-Set Drone and Fighter Manufacturing Efficiency II',
    		'typeID' 		=> 43713,
    		'install_price'  => '137738752.84',
    		'materials' =>   [
    			'25612'=>10154,
    			'25613'=>1693,
    			'25610'=>1693,
    			'25611'=>10154,
    			'25617'=>136,
    			'25618'=>10154,
    			'25609'=>170,
    			'25616'=>8461,
    			'25623'=>1693,
    			'25620'=>237,
    			'11475'=>1,
    		]
    	];

    	$rig['43714'] = [

    		'name'			=> 'Standup L-Set Basic Small Ship Manufacturing Efficiency I',
    		'typeID' 		=> 43714,
    		'install_price'  => '12135008.75',
    		'materials' =>   [
    			'25591'=>10154,
    			'25590'=>6092,
    			'25594'=>10154,
    			'25592'=>10154,
    			'25604'=>10154,
    			'25593'=>10154,
    			'25601'=>9183,
    			'25597'=>10154,
    			'25598'=>3046,
    			'25599'=>10154,
    		]
    	];

    	$rig['43715'] = [

    		'name'			=> 'Standup L-Set Basic Small Ship Manufacturing Efficiency II',
    		'typeID' 		=> 43715,
    		'install_price'  => '137738752.84',
    		'materials' =>   [
    			'25612'=>10154,
    			'25613'=>1693,
    			'25610'=>1693,
    			'25611'=>10154,
    			'25617'=>136,
    			'25618'=>10154,
    			'25609'=>170,
    			'25616'=>8461,
    			'25623'=>1693,
    			'25620'=>237,
    			'11475'=>1,
    		]
    	];

    	$rig['43716'] = [

    		'name'			=> 'Standup L-Set Basic Medium Ship Manufacturing Efficiency I',
    		'typeID' 		=> 43716,
    		'install_price'  => '12135008.75',
    		'materials' =>   [
    			'25591'=>10154,
    			'25590'=>6092,
    			'25594'=>10154,
    			'25592'=>10154,
    			'25604'=>10154,
    			'25593'=>10154,
    			'25601'=>9183,
    			'25597'=>10154,
    			'25598'=>3046,
    			'25599'=>10154,
    		]
    	];

    	$rig['43717'] = [

    		'name'			=> 'Standup L-Set Basic Medium Ship Manufacturing Efficiency II',
    		'typeID' 		=> 43717,
    		'install_price'  => '137738752.84',
    		'materials' =>   [
    			'25612'=>10154,
    			'25613'=>1693,
    			'25610'=>1693,
    			'25611'=>10154,
    			'25617'=>136,
    			'25618'=>10154,
    			'25609'=>170,
    			'25616'=>8461,
    			'25623'=>1693,
    			'25620'=>237,
    			'11475'=>1,
    		]
    	];
    	$rig['37166'] = [

    		'name'			=> 'Standup L-Set Basic Large Ship Manufacturing Efficiency I',
    		'typeID' 		=> 37166,
    		'install_price'  => '12135008.75',
    		'materials' =>   [
    			'25591'=>10154,
    			'25590'=>6092,
    			'25594'=>10154,
    			'25592'=>10154,
    			'25604'=>10154,
    			'25593'=>10154,
    			'25601'=>9183,
    			'25597'=>10154,
    			'25598'=>3046,
    			'25599'=>10154,
    		]
    	];

    	$rig['37167'] = [

    		'name'			=> 'Standup L-Set Basic Large Ship Manufacturing Efficiency II',
    		'typeID' 		=> 37167,
    		'install_price'  => '137738752.84',
    		'materials' =>   [
    			'25612'=>10154,
    			'25613'=>1693,
    			'25610'=>1693,
    			'25611'=>10154,
    			'25617'=>136,
    			'25618'=>10154,
    			'25609'=>170,
    			'25616'=>8461,
    			'25623'=>1693,
    			'25620'=>237,
    			'11475'=>1,
    		]
    	];

    	$rig['43707'] = [

    		'name'			=> 'Standup L-Set Advanced Small Ship Manufacturing Efficiency I',
    		'typeID' 		=> 43707,
    		'install_price'  => '12135008.75',
    		'materials' =>   [
    			'25591'=>10154,
    			'25590'=>6092,
    			'25594'=>10154,
    			'25592'=>10154,
    			'25604'=>10154,
    			'25593'=>10154,
    			'25601'=>9183,
    			'25597'=>10154,
    			'25598'=>3046,
    			'25599'=>10154,
    		]
    	];

    	$rig['43708'] = [

    		'name'			=> 'Standup L-Set Advanced Small Ship Manufacturing Efficiency II',
    		'typeID' 		=> 43708,
    		'install_price'  => '137738752.84',
    		'materials' =>   [
    			'25612'=>10154,
    			'25613'=>1693,
    			'25610'=>1693,
    			'25611'=>10154,
    			'25617'=>136,
    			'25618'=>10154,
    			'25609'=>170,
    			'25616'=>8461,
    			'25623'=>1693,
    			'25620'=>237,
    			'11475'=>1,
    		]
    	];

    	$rig['43709'] = [

    		'name'			=> 'Standup L-Set Advanced Medium Ship Manufacturing Efficiency I',
    		'typeID' 		=> 43709,
    		'install_price'  => '12135008.75',
    		'materials' =>   [
    			'25591'=>10154,
    			'25590'=>6092,
    			'25594'=>10154,
    			'25592'=>10154,
    			'25604'=>10154,
    			'25593'=>10154,
    			'25601'=>9183,
    			'25597'=>10154,
    			'25598'=>3046,
    			'25599'=>10154,
    		]
    	];

    	$rig['43711'] = [

    		'name'			=> 'Standup L-Set Advanced Medium Ship Manufacturing Efficiency II',
    		'typeID' 		=> 43711,
    		'install_price'  => '137738752.84',
    		'materials' =>   [
    			'25612'=>10154,
    			'25613'=>1693,
    			'25610'=>1693,
    			'25611'=>10154,
    			'25617'=>136,
    			'25618'=>10154,
    			'25609'=>170,
    			'25616'=>8461,
    			'25623'=>1693,
    			'25620'=>237,
    			'11475'=>1,
    		]
    	];

    	$rig['37168'] = [

    		'name'			=> 'Standup L-Set Advanced Large Ship Manufacturing Efficiency I',
    		'typeID' 		=> 37168,
    		'install_price'  => '12135008.75',
    		'materials' =>   [
    			'25591'=>10154,
    			'25590'=>6092,
    			'25594'=>10154,
    			'25592'=>10154,
    			'25604'=>10154,
    			'25593'=>10154,
    			'25601'=>9183,
    			'25597'=>10154,
    			'25598'=>3046,
    			'25599'=>10154,
    		]
    	];

    	$rig['37169'] = [

    		'name'			=> 'Standup L-Set Advanced Large Ship Manufacturing Efficiency II',
    		'typeID' 		=> 37169,
    		'install_price'  => '137738752.84',
    		'materials' =>   [
    			'25612'=>10154,
    			'25613'=>1693,
    			'25610'=>1693,
    			'25611'=>10154,
    			'25617'=>136,
    			'25618'=>10154,
    			'25609'=>170,
    			'25616'=>8461,
    			'25623'=>1693,
    			'25620'=>237,
    			'11475'=>1,
    		]
    	];

    	$rig['37173'] = [

    		'name'			=> 'Standup L-Set Capital Ship Manufacturing Efficiency I',
    		'typeID' 		=> 37173,
    		'install_price'  => '12135008.75',
    		'materials' =>   [
    			'25591'=>10154,
    			'25590'=>6092,
    			'25594'=>10154,
    			'25592'=>10154,
    			'25604'=>10154,
    			'25593'=>10154,
    			'25601'=>9183,
    			'25597'=>10154,
    			'25598'=>3046,
    			'25599'=>10154,
    		]
    	];

    	$rig['37172'] = [

    		'name'			=> 'Standup L-Set Advanced Large Ship Manufacturing Efficiency II',
    		'typeID' 		=> 37172,
    		'install_price'  => '137738752.84',
    		'materials' =>   [
    			'25612'=>10154,
    			'25613'=>1693,
    			'25610'=>1693,
    			'25611'=>10154,
    			'25617'=>136,
    			'25618'=>10154,
    			'25609'=>170,
    			'25616'=>8461,
    			'25623'=>1693,
    			'25620'=>237,
    			'11475'=>1,
    		]
    	];

    	$rig['37174'] = [

    		'name'			=> 'Standup L-Set Advanced Component Manufacturing Efficiency I',
    		'typeID' 		=> 37174,
    		'install_price'  => '12135008.75',
    		'materials' =>   [
    			'25591'=>10154,
    			'25590'=>6092,
    			'25594'=>10154,
    			'25592'=>10154,
    			'25604'=>10154,
    			'25593'=>10154,
    			'25601'=>9183,
    			'25597'=>10154,
    			'25598'=>3046,
    			'25599'=>10154,
    		]
    	];

    	$rig['37175'] = [

    		'name'			=> 'Standup L-Set Advanced Component Manufacturing Efficiency II',
    		'typeID' 		=> 37175,
    		'install_price'  => '137738752.84',
    		'materials' =>   [
    			'25612'=>10154,
    			'25613'=>1693,
    			'25610'=>1693,
    			'25611'=>10154,
    			'25617'=>136,
    			'25618'=>10154,
    			'25609'=>170,
    			'25616'=>8461,
    			'25623'=>1693,
    			'25620'=>237,
    			'11475'=>1,
    		]
    	];

    	$rig['43718'] = [

    		'name'			=> 'Standup L-Set Basic Capital Component Manufacturing Efficiency I',
    		'typeID' 		=> 43718,
    		'install_price'  => '12135008.75',
    		'materials' =>   [
    			'25591'=>10154,
    			'25590'=>6092,
    			'25594'=>10154,
    			'25592'=>10154,
    			'25604'=>10154,
    			'25593'=>10154,
    			'25601'=>9183,
    			'25597'=>10154,
    			'25598'=>3046,
    			'25599'=>10154,
    		]
    	];

    	$rig['43719'] = [

    		'name'			=> 'Standup L-Set Basic Capital Component Manufacturing Efficiency II',
    		'typeID' 		=> 43719,
    		'install_price'  => '137738752.84',
    		'materials' =>   [
    			'25612'=>10154,
    			'25613'=>1693,
    			'25610'=>1693,
    			'25611'=>10154,
    			'25617'=>136,
    			'25618'=>10154,
    			'25609'=>170,
    			'25616'=>8461,
    			'25623'=>1693,
    			'25620'=>237,
    			'11475'=>1,
    		]
    	];

    	$rig['43720'] = [

    		'name'			=> 'Standup L-Set Structure Manufacturing Efficiency I',
    		'typeID' 		=> 43720,
    		'install_price'  => '12135008.75',
    		'materials' =>   [
    			'25591'=>10154,
    			'25590'=>6092,
    			'25594'=>10154,
    			'25592'=>10154,
    			'25604'=>10154,
    			'25593'=>10154,
    			'25601'=>9183,
    			'25597'=>10154,
    			'25598'=>3046,
    			'25599'=>10154,
    		]
    	];

    	$rig['43721'] = [

    		'name'			=> 'Standup L-Set Structure Manufacturing Efficiency II',
    		'typeID' 		=> 43721,
    		'install_price'  => '137738752.84',
    		'materials' =>   [
    			'25612'=>10154,
    			'25613'=>1693,
    			'25610'=>1693,
    			'25611'=>10154,
    			'25617'=>136,
    			'25618'=>10154,
    			'25609'=>170,
    			'25616'=>8461,
    			'25623'=>1693,
    			'25620'=>237,
    			'11475'=>1,
    		]
    	];

    	$rig['43724'] = [

    		'name'			=> 'Standup L-Set Invention Optimization I',
    		'typeID' 		=> 43724,
    		'install_price'  => '19058832.49',
    		'materials' =>   [
    			'25596'=>20307,
    			'25602'=>20307,
    			'25592'=>20307,
    			'25603'=>10154,
    			'25588'=>20307,
    			'25593'=>20307,
    			'25600'=>2031,
    			'25597'=>20307,
    			'25601'=>10154,
    			'25599'=>20307,

    		]
    	];

    	$rig['43723'] = [

    		'name'			=> 'Standup L-Set Invention Optimization II',
    		'typeID' 		=> 43723,
    		'install_price'  => '117963109.54',
    		'materials' =>   [
    			'25612'=>5923,
    			'25621'=>11000,
    			'25612'=>5923,
    			'25611'=>5923,
    			'25619'=>136,
    			'25607'=>5923,
    			'25622'=>136,
    			'25618'=>5923,
    			'25616'=>5923,
    			'25620'=>136,
    			'11483'=>1,

    		]
    	];

    	$rig['43724'] = [

    		'name'			=> 'Standup L-Set ME Research Optimization I',
    		'typeID' 		=> 43724,
    		'install_price'  => '19058832.49',
    		'materials' =>   [
    			'25596'=>20307,
    			'25602'=>20307,
    			'25592'=>20307,
    			'25603'=>10154,
    			'25588'=>20307,
    			'25593'=>20307,
    			'25600'=>2031,
    			'25597'=>20307,
    			'25601'=>10154,
    			'25599'=>20307,

    		]
    	];

    	$rig['43725'] = [

    		'name'			=> 'Standup L-Set ME Research Optimization II',
    		'typeID' 		=> 43725,
    		'install_price'  => '117963109.54',
    		'materials' =>   [
    			'25612'=>5923,
    			'25621'=>11000,
    			'25612'=>5923,
    			'25611'=>5923,
    			'25619'=>136,
    			'25607'=>5923,
    			'25622'=>136,
    			'25618'=>5923,
    			'25616'=>5923,
    			'25620'=>136,
    			'11483'=>1,

    		]
    	];

    	$rig['43726'] = [

    		'name'			=> 'Standup L-Set TE Research Optimization I',
    		'typeID' 		=> 43726,
    		'install_price'  => '19058832.49',
    		'materials' =>   [
    			'25596'=>20307,
    			'25602'=>20307,
    			'25592'=>20307,
    			'25603'=>10154,
    			'25588'=>20307,
    			'25593'=>20307,
    			'25600'=>2031,
    			'25597'=>20307,
    			'25601'=>10154,
    			'25599'=>20307,

    		]
    	];

    	$rig['43727'] = [

    		'name'			=> 'Standup L-Set TE Research Optimization II',
    		'typeID' 		=> 43727,
    		'install_price'  => '117963109.54',
    		'materials' =>   [
    			'25612'=>5923,
    			'25621'=>11000,
    			'25612'=>5923,
    			'25611'=>5923,
    			'25619'=>136,
    			'25607'=>5923,
    			'25622'=>136,
    			'25618'=>5923,
    			'25616'=>5923,
    			'25620'=>136,
    			'11483'=>1,

    		]
    	];

    	$rig['43729'] = [

    		'name'			=> 'Standup L-Set Blueprint Copy Optimization I',
    		'typeID' 		=> 43729,
    		'install_price'  => '19058832.49',
    		'materials' =>   [
    			'25596'=>20307,
    			'25602'=>20307,
    			'25592'=>20307,
    			'25603'=>10154,
    			'25588'=>20307,
    			'25593'=>20307,
    			'25600'=>2031,
    			'25597'=>20307,
    			'25601'=>10154,
    			'25599'=>20307,

    		]
    	];

    	$rig['43730'] = [

    		'name'			=> 'Standup L-Set Blueprint Copy Optimization II',
    		'typeID' 		=> 43730,
    		'install_price'  => '117963109.54',
    		'materials' =>   [
    			'25612'=>5923,
    			'25621'=>11000,
    			'25612'=>5923,
    			'25611'=>5923,
    			'25619'=>136,
    			'25607'=>5923,
    			'25622'=>136,
    			'25618'=>5923,
    			'25616'=>5923,
    			'25620'=>136,
    			'11483'=>1,

    		]
    	];

    	$rig['37164'] = [

    		'name'			=> 'Standup L-Set Ammunition Manufacturing Efficiency I',
    		'typeID' 		=> 37164,
    		'install_price'  => '12135008.75',
    		'materials' =>   [
    			'25591'=>10154,
    			'25590'=>6092,
    			'25594'=>10154,
    			'25592'=>10154,
    			'25604'=>10154,
    			'25593'=>10154,
    			'25601'=>9138,
    			'25597'=>10154,
    			'25598'=>3046,
    			'25599'=>10154,
    		]
    	];

    	$rig['37165'] = [

    		'name'			=> 'Standup L-Set Ammunition Manufacturing Efficiency II',
    		'typeID' 		=> 37165,
    		'install_price'  => '137738797.46',
    		'materials' =>   [
    			'25612'=>10154,
    			'25613'=>1693,
    			'25610'=>1693,
    			'25611'=>10154,
    			'25617'=>136,
    			'25618'=>10154,
    			'25609'=>170,
    			'25616'=>8461,
    			'25623'=>1693,
    			'25620'=>237,
    			'11481'=>1,

    		]
    	];

    	$rig['37256'] = [

    		'name'			=> 'Standup L-Set Bomb Aimer I',
    		'typeID' 		=> 37256,
    		'install_price'  => '25129600.64',
    		'materials' =>   [
    			'25591'=>6346,
    			'25594'=>6346,
    			'25596'=>6346,
    			'25606'=>4321,
    			'25602'=>6346,
    			'25588'=>6346,
    			'25604'=>6346,
    			'25589'=>6346,
    			'25597'=>6346,
    			'25595'=>1375,
    		]
    	];

    	$rig['37257'] = [

    		'name'			=> 'Standup L-Set Bomb Aimer II',
    		'typeID' 		=> 37257,
    		'install_price'  => '130608006.41',
    		'materials' =>   [
    			'25615'=>9519,
    			'25621'=>1270,
    			'25613'=>1270,
    			'25625'=>51,
    			'25610'=>1270,
    			'25607'=>9519,
    			'25616'=>9519,
    			'25614'=>51,
    			'25608'=>51,
    			'25623'=>1270,
    			'11486'=>1,

    		]
    	];

    	$rig['37250'] = [

    		'name'			=> 'Standup L-Set Energy Neutralizer Feedback Control I',
    		'typeID' 		=> 37250,
    		'install_price'  => '25129600.64',
    		'materials' =>   [
    			'25591'=>6346,
    			'25594'=>6346,
    			'25596'=>6346,
    			'25606'=>4321,
    			'25602'=>6346,
    			'25588'=>6346,
    			'25604'=>6346,
    			'25589'=>6346,
    			'25597'=>6346,
    			'25595'=>1375,
    		]
    	];

    	$rig['37251'] = [

    		'name'			=> 'Standup L-Set Energy Neutralizer Feedback Control II',
    		'typeID' 		=> 37251,
    		'install_price'  => '130608006.41',
    		'materials' =>   [
    			'25615'=>9519,
    			'25621'=>1270,
    			'25613'=>1270,
    			'25625'=>51,
    			'25610'=>1270,
    			'25607'=>9519,
    			'25616'=>9519,
    			'25614'=>51,
    			'25608'=>51,
    			'25623'=>1270,
    			'11486'=>1,

    		]
    	];

    	$rig['37254'] = [

    		'name'			=> 'Standup L-Set EW Expert System I',
    		'typeID' 		=> 37254,
    		'install_price'  => '25129600.64',
    		'materials' =>   [
    			'25591'=>6346,
    			'25594'=>6346,
    			'25596'=>6346,
    			'25606'=>4321,
    			'25602'=>6346,
    			'25588'=>6346,
    			'25604'=>6346,
    			'25589'=>6346,
    			'25597'=>6346,
    			'25595'=>1375,
    		]
    	];

    	$rig['37255'] = [

    		'name'			=> 'Standup L-Set EW Expert System II',
    		'typeID' 		=> 37255,
    		'install_price'  => '130608006.41',
    		'materials' =>   [
    			'25615'=>9519,
    			'25621'=>1270,
    			'25613'=>1270,
    			'25625'=>51,
    			'25610'=>1270,
    			'25607'=>9519,
    			'25616'=>9519,
    			'25614'=>51,
    			'25608'=>51,
    			'25623'=>1270,
    			'11486'=>1,

    		]
    	];

    	$rig['37248'] = [

    		'name'			=> 'Standup L-Set Missile Flight Processor I',
    		'typeID' 		=> 37248,
    		'install_price'  => '25129600.64',
    		'materials' =>   [
    			'25591'=>6346,
    			'25594'=>6346,
    			'25596'=>6346,
    			'25606'=>4321,
    			'25602'=>6346,
    			'25588'=>6346,
    			'25604'=>6346,
    			'25589'=>6346,
    			'25597'=>6346,
    			'25595'=>1375,
    		]
    	];

    	$rig['37249'] = [

    		'name'			=> 'Standup L-Set Missile Flight Processor II',
    		'typeID' 		=> 37249,
    		'install_price'  => '130608006.41',
    		'materials' =>   [
    			'25615'=>9519,
    			'25621'=>1270,
    			'25613'=>1270,
    			'25625'=>51,
    			'25610'=>1270,
    			'25607'=>9519,
    			'25616'=>9519,
    			'25614'=>51,
    			'25608'=>51,
    			'25623'=>1270,
    			'11486'=>1,

    		]
    	];

    	$rig['46327'] = [

    		'name'			=> 'Standup L-Set Moon Drilling Proficiency I',
    		'typeID' 		=> 46327,
    		'install_price'  => '111953514.68',
    		'materials' =>   [
    			'25591'=>10154,
    			'25605'=>10154,
    			'25594'=>10154,
    			'25600'=>10154,
    			'25592'=>20307,
    			'25603'=>10154,
    			'25601'=>10154,
    			'25589'=>10154,
    			'25597'=>10154,
    			'25599'=>20307,
    		]
    	];

    	$rig['46328'] = [

    		'name'			=> 'Standup L-Set Moon Drilling Proficiency II',
    		'typeID' 		=> 46328,
    		'install_price'  => '654816301.57',
    		'materials' =>   [

    			'25613'=>6431,
    			'25617'=>6431,
    			'25610'=>10154,
    			'25625'=>136,
    			'25619'=>247,
    			'25620'=>6431,
    			'25608'=>136,
    			'25622'=>237,
    			'25609'=>237,
    			'25616'=>10154,
    			'11481'=>1,
    		]
    	];

    	$rig['37258'] = [

    		'name'			=> 'Standup L-Set Point Defense Battery Control I',
    		'typeID' 		=> 37258,
    		'install_price'  => '25129600.64',
    		'materials' =>   [
    			'25591'=>6346,
    			'25594'=>6346,
    			'25596'=>6346,
    			'25606'=>4321,
    			'25602'=>6346,
    			'25588'=>6346,
    			'25604'=>6346,
    			'25589'=>6346,
    			'25597'=>6346,
    			'25595'=>1375,
    		]
    	];

    	$rig['37259'] = [

    		'name'			=> 'Standup L-Set Point Defense Battery Control II',
    		'typeID' 		=> 37259,
    		'install_price'  => '130608006.41',
    		'materials' =>   [
    			'25615'=>9519,
    			'25621'=>1270,
    			'25613'=>1270,
    			'25625'=>51,
    			'25610'=>1270,
    			'25607'=>9519,
    			'25616'=>9519,
    			'25614'=>51,
    			'25608'=>51,
    			'25623'=>1270,
    			'11486'=>1,

    		]
    	];

    	$rig['46496'] = [

    		'name'			=> 'Standup L-Set Reactor Efficiency I',
    		'typeID' 		=> 46496,
    		'install_price'  => '272683658.25',
    		'materials' =>   [
    			'25590'=>10154,
    			'25605'=>20307,
    			'25606'=>17768,
    			'25594'=>8884,
    			'25588'=>10154,
    			'25593'=>10154,
    			'25589'=>40613,
    			'25595'=>10154,
    			'25601'=>20307,
    			'25599'=>10154,    		
    		]
    	];

    	$rig['46497'] = [

    		'name'			=> 'Standup L-Set Reactor Efficiency II',
    		'typeID' 		=> 46497,
    		'install_price'  => '351699180.14',
    		'materials' =>   [
    			'25612'=>6431,
    			'25610'=>10154,
    			'25624'=>237,
    			'25613'=>237,
    			'25614'=>136,
    			'25607'=>6431,
    			'25618'=>10154,
    			'25616'=>10154,
    			'25611'=>1693,
    			'25609'=>136,
    			'11481'=>1,
    		]
    	];

    	$rig['46639'] = [

    		'name'			=> 'Standup L-Set Reprocessing Monitor I',
    		'typeID' 		=> 46639,
    		'install_price'  => '10690981.47',
    		'materials' =>   [
    			'25591'=>14807,
    			'25590'=>9519,
    			'25594'=>14807,
    			'25596'=>16922,
    			'25592'=>16922,
    			'25588'=>16922,
    			'25593'=>16922,
    			'25605'=>424,
    			'25599'=>16922,
    			'25595'=>424,  		
    		]
    	];

    	$rig['46640'] = [

    		'name'			=> 'Standup L-Set Reprocessing Monitor II',
    		'typeID' 		=> 46640,
    		'install_price'  => '226449444.55',
    		'materials' =>   [
    			'25615'=>9646,
    			'25612'=>9646,
    			'25613'=>3808,
    			'25610'=>3808,
    			'25611'=>9646,
    			'25624'=>26,
    			'25614'=>204,
    			'25607'=>9646,
    			'25618'=>9646,
    			'25609'=>204,
    			'11476'=>1,

    		]
    	];



    	$rig['37260'] = [

    		'name'			=> 'Standup L-Set Target Acquisition Array I',
    		'typeID' 		=> 37260,
    		'install_price'  => '25129600.64',
    		'materials' =>   [
    			'25591'=>6346,
    			'25594'=>6346,
    			'25596'=>6346,
    			'25606'=>4321,
    			'25602'=>6346,
    			'25588'=>6346,
    			'25604'=>6346,
    			'25589'=>6346,
    			'25597'=>6346,
    			'25595'=>1375,
    		]
    	];

    	$rig['37261'] = [

    		'name'			=> 'Standup L-Set Target Acquisition Array II',
    		'typeID' 		=> 37261,
    		'install_price'  => '130608006.41',
    		'materials' =>   [
    			'25615'=>9519,
    			'25621'=>1270,
    			'25613'=>1270,
    			'25625'=>51,
    			'25610'=>1270,
    			'25607'=>9519,
    			'25616'=>9519,
    			'25614'=>51,
    			'25608'=>51,
    			'25623'=>1270,
    			'11486'=>1,

    		]
    	];

    	$rig['37178'] = [

    		'name'			=> 'Standup XL-Set Equipment and Consumable Manufacturing Efficiency I',
    		'typeID' 		=> 37178,
    		'install_price'  => '121350087.54',
    		'materials' =>   [
    			'25591'=>101532,
    			'25590'=>60919,
    			'25594'=>101532,
    			'25592'=>101532,
    			'25604'=>101532,
    			'25593'=>101532,
    			'25601'=>91379,
    			'25597'=>101532,
    			'25598'=>30460,
    			'25599'=>101532,
    			
    		]
    	];

    	$rig['37179'] = [

    		'name'			=> 'Standup XL-Set Equipment and Consumable Manufacturing Efficiency II',
    		'typeID' 		=> 37179,
    		'install_price'  => '130608006.41',
    		'materials' =>   [
    			'25612'=>126915,
    			'25613'=>21153,
    			'25610'=>21153,
    			'25611'=>126915,
    			'25617'=>1693,
    			'25618'=>126915,
    			'25609'=>2116,
    			'25616'=>105762,
    			'25623'=>21153,
    			'25620'=>2962,
    			'11481'=>1,
    			

    		]
    	];


    	$rig['37272'] = [

    		'name'			=> 'Standup XL-Set EW and Emissions Co-ordinator I',
    		'typeID' 		=> 37272,
    		'install_price'  => '81821272.38',
    		'materials' =>   [
    			'25591'=>76149,
    			'25594'=>76149,
    			'25596'=>76149,
    			'25602'=>76149,
    			'25588'=>76149,
    			'25604'=>76149,
    			'25606'=>5077,
    			'25589'=>76149,
    			'25597'=>76149,
    			'25595'=>16499,    			
    		]
    	];

    	$rig['37273'] = [

    		'name'			=> 'Standup XL-Set EW and Emissions Co-ordinator II',
    		'typeID' 		=> 37273,
    		'install_price'  => '2612159778.62',
    		'materials' =>   [	
    			'25615'=>190372,
    			'25621'=>25383,
    			'25613'=>25383,
    			'25625'=>1016,
    			'25610'=>25383,
    			'25607'=>190372,
    			'25616'=>190372,
    			'25614'=>1016,
    			'25608'=>1016,
    			'25623'=>25383,
    			'11482'=>1,

    		]
    	];

    	$rig['37274'] = [

    		'name'			=> 'Standup XL-Set Extinction Level Weapons Suite I',
    		'typeID' 		=> 37274,
    		'install_price'  => '81821272.38',
    		'materials' =>   [
    			'25591'=>76149,
    			'25594'=>76149,
    			'25596'=>76149,
    			'25602'=>76149,
    			'25588'=>76149,
    			'25604'=>76149,
    			'25606'=>5077,
    			'25589'=>76149,
    			'25597'=>76149,
    			'25595'=>16499,    			
    		]
    	];

    	$rig['37275'] = [

    		'name'			=> 'Standup XL-Set Extinction Level Weapons Suite II',
    		'typeID' 		=> 37275,
    		'install_price'  => '2612159771.04',
    		'materials' =>   [	
    			'25615'=>190372,
    			'25621'=>25383,
    			'25613'=>25383,
    			'25625'=>1016,
    			'25610'=>25383,
    			'25607'=>190372,
    			'25616'=>190372,
    			'25614'=>1016,
    			'25608'=>1016,
    			'25623'=>25383,
    			'11482'=>1,

    		]
    	];

    	$rig['37183'] = [

    		'name'			=> 'Standup XL-Set Laboratory Optimization I',
    		'typeID' 		=> 37183,
    		'install_price'  => '190588324.87',
    		'materials' =>   [
    			'25596'=>203063,
    			'25602'=>203063,
    			'25592'=>203063,
    			'25603'=>101532,
    			'25588'=>203063,
    			'25593'=>203063,
    			'25600'=>20307,
    			'25597'=>203063,
    			'25601'=>101532,
    			'25599'=>203063,    			    			
    		]
    	];

    	$rig['37182'] = [

    		'name'			=> 'Standup XL-Set Laboratory Optimization II',
    		'typeID' 		=> 37182,
    		'install_price'  => '1474538044.07',
    		'materials' =>   [	
    			'25615'=>74034,
    			'25621'=>137491,
    			'25612'=>74034,
    			'25611'=>74034,
    			'25619'=>1693,
    			'25607'=>74034,
    			'25622'=>1693,
    			'25618'=>74034,
    			'25616'=>74034,
    			'25620'=>1693,
    			'11483'=>1,    		

    		]
    	];

    	$rig['37268'] = [

    		'name'			=> 'Standup XL-Set Missile Fire Control Computer I',
    		'typeID' 		=> 37268,
    		'install_price'  => '81821272.38',
    		'materials' =>   [
    			'25591'=>76149,
    			'25594'=>76149,
    			'25596'=>76149,
    			'25602'=>76149,
    			'25588'=>76149,
    			'25604'=>76149,
    			'25606'=>5077,
    			'25589'=>76149,
    			'25597'=>76149,
    			'25595'=>16499,    			
    		]
    	];

    	$rig['37269'] = [

    		'name'			=> 'Standup XL-Set Missile Fire Control Computer II',
    		'typeID' 		=> 37269,
    		'install_price'  => '2612159771.04',
    		'materials' =>   [	
    			'25615'=>190372,
    			'25621'=>25383,
    			'25613'=>25383,
    			'25625'=>1016,
    			'25610'=>25383,
    			'25607'=>190372,
    			'25616'=>190372,
    			'25614'=>1016,
    			'25608'=>1016,
    			'25623'=>25383,
    			'11482'=>1,

    		]
    	];


    	$rig['46641'] = [

    		'name'			=> 'Standup XL-Set Reprocessing Monitor I',
    		'typeID' 		=> 46641,
    		'install_price'  => '10690981.47',
    		'materials' =>   [
    			'25591'=>14807,
    			'25590'=>9519,
    			'25594'=>14807,
    			'25596'=>16922,
    			'25592'=>16922,
    			'25588'=>16922,
    			'25593'=>16922,
    			'25605'=>424,
    			'25599'=>16922,
    			'25595'=>424,  		
    		]
    	];

    	$rig['46642'] = [

    		'name'			=> 'Standup XL-Set Reprocessing Monitor II',
    		'typeID' 		=> 46642,
    		'install_price'  => '226449444.55',
    		'materials' =>   [
    			'25615'=>9646,
    			'25612'=>9646,
    			'25613'=>3808,
    			'25610'=>3808,
    			'25611'=>9646,
    			'25624'=>26,
    			'25614'=>204,
    			'25607'=>9646,
    			'25618'=>9646,
    			'25609'=>204,
    			'11476'=>1,

    		]
    	];

    	$rig['37180'] = [

    		'name'			=> 'Standup XL-Set Ship Manufacturing Efficiency I',
    		'typeID' 		=> 37180,
    		'install_price'  => '121350087.54',
    		'materials' =>   [
    			'25591'=>101532,
    			'25590'=>60919,
    			'25594'=>101532,
    			'25592'=>101532,
    			'25604'=>101532,
    			'25593'=>101532,
    			'25601'=>91379,
    			'25597'=>101532,
    			'25598'=>30460,
    			'25599'=>101532,
    			
    		]
    	];

    	$rig['37181'] = [

    		'name'			=> 'Standup XL-Set Ship Manufacturing Efficiency II',
    		'typeID' 		=> 37181,
    		'install_price'  => '130608006.41',
    		'materials' =>   [
    			'25612'=>126915,
    			'25613'=>21153,
    			'25610'=>21153,
    			'25611'=>126915,
    			'25617'=>1693,
    			'25618'=>126915,
    			'25609'=>2116,
    			'25616'=>105762,
    			'25623'=>21153,
    			'25620'=>2962,
    			'11481'=>1,
    			

    		]
    	];

    	$rig['43704'] = [

    		'name'			=> 'Standup XL-Set Structure and Component Manufacturing Efficiency I',
    		'typeID' 		=> 43704,
    		'install_price'  => '121350087.54',
    		'materials' =>   [
    			'25591'=>101532,
    			'25590'=>60919,
    			'25594'=>101532,
    			'25592'=>101532,
    			'25604'=>101532,
    			'25593'=>101532,
    			'25601'=>91379,
    			'25597'=>101532,
    			'25598'=>30460,
    			'25599'=>101532,
    			
    		]
    	];

    	$rig['43705'] = [

    		'name'			=> 'Standup XL-Set Structure and Component Manufacturing Efficiency II',
    		'typeID' 		=> 43705,
    		'install_price'  => '130608006.41',
    		'materials' =>   [
    			'25612'=>126915,
    			'25613'=>21153,
    			'25610'=>21153,
    			'25611'=>126915,
    			'25617'=>1693,
    			'25618'=>126915,
    			'25609'=>2116,
    			'25616'=>105762,
    			'25623'=>21153,
    			'25620'=>2962,
    			'11481'=>1,
    			

    		]
    	];


    	$collection = collect($rig);  //Make the nested array into a collection

    	foreach ($collection as $id => $material) {

    		$this->getInformation($id, json_encode($material));			  // Cycle Array and Send TypeID and JSON of Build Materials to save into DB.

    	}

    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
	public function getInformation($id, $json) { // End Point: https://esi.evetech.net/ui/#/Universe/get_universe_types_type_id

		$configuration = Configuration::getInstance();

		$client_id = config('eve.client_id');
		$secret_key = config('eve.secret_key');

		$esi = new Eseye();

		try { 

			$data = $esi->invoke('get', '/universe/types/{type_id}/', [   
				'type_id' => $id,
			]);

			$updateore = UpwellRigs::updateOrCreate([
				'type_id'      				=> $id,
			],[
				'name'  					=> $data->name,
				'description'  				=> $data->description,
				'group_id'  				=> $data->group_id,
				'icon_id'  					=> $data->icon_id,
				'meta_data'  				=> $json
			]);

			$this->info('Rig Added: ' . $data->name);

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
