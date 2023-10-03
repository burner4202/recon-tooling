<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

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

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class NPCKillsController extends Controller
{
        /**
     * Display all regions & stats
     *
     * @return Response
    */
        public function regions()
        {
        	$regions = SolarSystems::groupBy('ss_region_name')
            ->orderBy('ss_region_name', 'asc')
            # Exclude wormholes and jove space, as we have a stargate.
            ->where('ss_stargates', '!=', '""')
        	->get();

        	$allRegions = array();

        	$regionPerHour = array();
        	$regionPerDay = array();

        	foreach ($regions as $region) {

        		$allRegions[$region->ss_region_id] = [

        			'region_id' => $region->ss_region_id,
        			'region_name' => $region->ss_region_name,
        			//'description' => $region->description,
        			//'constellations' => $region->constellations,
        			'kills_past_hour' => $this->regionHourlyKills($region->ss_region_id),
    			//'kills_past_two_hours' => $this->regionHourlyKills($region->region_id),
        			'kills_past_24_hours' => $this->regionDailyKills($region->ss_region_id),
        		];

        	}

        	$allRegionMetrics = $this->allRegionMetrics();

        	ksort($allRegionMetrics);
        	unset($allRegionMetrics['Orphen']);

        	return view('npckills.regions', compact('allRegions', 'allRegionMetrics'));

        }

    /**
     * Display all regions & stats
     *
     * @return Response
    */
    public function region($region_id)
    {

    	$allSystems = array();

    	$systems = SolarSystems::where('ss_region_id', $region_id)
    	->orderBy('ss_system_name', 'ASC')
    	->get();

    	$region = SolarSystems::where('ss_region_id', $region_id)
    	->first();

    	$systemPerHour = array();
    	$systemPerDay = array();

    	foreach ($systems as $system) {

    		$allSystems[$system->ss_system_id] = [

    			'system_id' => $system->ss_system_id,
    			'system_name' => $system->ss_system_name,
    			'constellation_id' => $system->ss_constellation_id,
    			'constellation_name' => $system->ss_constellation_name,
    			'region_id' => $system->ss_region_id,
    			'region_name' => $system->ss_region_name,
    			//'security_class' => $system->security_class,
    			//'security_status' => $system->security_status,
    			'kills_past_hour' => $this->systemHourlyKills($system->ss_system_id),
                //'kills_past_two_hours' => $this->regionHourlyKills($system->system_id),
    			'kills_past_24_hours' => $this->systemDailyKills($system->ss_system_id),
    		];



    		//$systemPerHour[$system->system_id] = $this->systemHourlyKills($system->system_id);
    		//$systemPerDay[$system->system_id] = $this->systemDailyKills($system->system_id);
    	}

    	$regionMetrics24Hours = $this->regionMetrics24Hours($region_id);
    	$regionMetrics48Hours = $this->regionMetrics48Hours($region_id);

    	$rattingSchedule = NPCKills::where('region_id', $region_id)
    	->where('created_at', '>=', Carbon::now()->subDay(3))
    	->orderBy('npc_kills', 'DESC')
    	->groupBy('updated_at')
    	->take(9)
    	->get();


    	return view('npckills.region', compact('systems', 'systemPerHour', 'systemPerDay', 'region', 'regionMetrics24Hours', 'regionMetrics48Hours', 'allSystems', 'rattingSchedule'));

    }

    public function system($system_id)
    {

    	$systems = NPCKills::where('solar_system_id', $system_id)
    	->orderBy('updated_at', 'DESC')
    	->take(24)
    	->get();

    	$systemDetails = SolarSystems::where('ss_system_id', $system_id)
    	->first();

    	$systemMetrics24Hours = $this->systemMetrics24Hours($system_id);
    	$systemMetrics48Hours = $this->systemMetrics48Hours($system_id);

    	$rattingSchedule = NPCKills::where('solar_system_id', $system_id)
    	->where('created_at', '>=', Carbon::now()->subDay(3))
    	->orderBy('npc_kills', 'DESC')
    	->groupBy('updated_at')
    	->take(9)
    	->get();

    	return view('npckills.system', compact('systems', 'systemMetrics24Hours', 'systemMetrics48Hours',  'rattingSchedule', 'systemDetails'));

    }

    public function regionHourlyKills($region_id)
    {

    	$kills_past_hour = DB::table('npc_kills')
    	->where('region_id', $region_id)
    	->where('created_at', '>=', Carbon::now()->subHours(1))
    	->select(DB::raw('DATE(created_at) as date'), DB::raw('HOUR(created_at) as hour'), DB::raw('sum(npc_kills) as npc_kills'))
    	->groupBy('hour')
    	->orderBy('hour', 'DESC')
    	->first();

    	if(isset($kills_past_hour->npc_kills)) { $kills = $kills_past_hour->npc_kills; } else { $kills = 0; }

    	return $kills;

    }

    public function regionDailyKills($region_id)
    {

    	$kills_past_day = DB::table('npc_kills')
    	->where('region_id', $region_id)
    	->where('created_at', '>=', Carbon::now()->subDay())
    	->select(DB::raw('DATE(created_at) as date'), DB::raw('DAY(created_at) as day'), DB::raw('sum(npc_kills) as npc_kills'))
    	//->groupBy('hour')
    	//->orderBy('date', 'DESC')
    	->first();

    	return $kills_past_day->npc_kills;
    }

    public function systemHourlyKills($system_id)
    {

    	$kills_past_hour = DB::table('npc_kills')
    	->where('solar_system_id', $system_id)
    	->where('created_at', '>=', Carbon::now()->subHours(1))
    	->select(DB::raw('DATE(created_at) as date'), DB::raw('HOUR(created_at) as hour'), DB::raw('sum(npc_kills) as npc_kills'))
    	->groupBy('hour')
    	->orderBy('hour', 'DESC')
    	->first();

    	if(isset($kills_past_hour->npc_kills)) { $kills = $kills_past_hour->npc_kills; } else { $kills = 0; }

    	return $kills;

    }

    public function systemDailyKills($system_id)
    {


    	$kills_past_day = DB::table('npc_kills')
    	->where('solar_system_id', $system_id)
    	->where('created_at', '>=', Carbon::now()->subDay())
    	->select(DB::raw('DATE(created_at) as date'), DB::raw('DAY(created_at) as day'), DB::raw('sum(npc_kills) as npc_kills'))
        //->groupBy('hour')
        //->orderBy('date', 'DESC')
    	->first();

    	return $kills_past_day->npc_kills;

    }

    public function systemMetrics24Hours($system_id) {

    	$systemDaily  = array();
    	$now = Carbon::now();


    	$npcMetrics = DB::table('npc_kills')
    	->where('solar_system_id', $system_id)
    	->where('created_at', '>=', $now->subDay(1)->toDateTimeString())
        //->whereBetween('created_at', [Carbon::now()->subDay(2),Carbon::now()->subDay()])
    	->select(DB::raw('DATE(created_at) as date'), DB::raw('HOUR(created_at) as hour'), DB::raw('sum(npc_kills) as npc_kills'), DB::raw('updated_at as updated_at'))
    	->groupBy('hour')
    	->orderBy('updated_at', 'ASC')
    	->get();


    	foreach($npcMetrics as $dailyGraph) {

    		$systemDaily[$dailyGraph->updated_at] = $dailyGraph->npc_kills;
    	}

    	return $systemDaily;

    }

    public function systemMetrics48Hours($system_id) {

    	$systemDaily  = array();


    	$npcMetrics = DB::table('npc_kills')
    	->where('solar_system_id', $system_id)
        //->where('created_at', '>=', Carbon::now()->subDay())
    	->whereBetween('created_at', [Carbon::now()->subDay(2),Carbon::now()->subDay()])
    	->select(DB::raw('DATE(created_at) as date'), DB::raw('HOUR(created_at) as hour'), DB::raw('sum(npc_kills) as npc_kills'), DB::raw('updated_at as updated_at'))
    	->groupBy('hour')
    	->orderBy('updated_at', 'ASC')
    	->get();

    	foreach($npcMetrics as $dailyGraph) {

    		$systemDaily[$dailyGraph->updated_at] = $dailyGraph->npc_kills;
    	}

    	return $systemDaily;

    }



    public function regionMetrics24Hours($region_id) {

    	$regionDaily  = array();
    	$now = Carbon::now();


    	$npcMetrics = DB::table('npc_kills')
    	->where('region_id', $region_id)
    	->where('created_at', '>=', $now->subDay(1)->toDateTimeString())
        //->whereBetween('created_at', [$now->subHours(24)->toDateTimeString(), $now->toDateTimeString()])
    	->select(DB::raw('DATE(created_at) as date'), DB::raw('HOUR(created_at) as hour'), DB::raw('sum(npc_kills) as npc_kills'), DB::raw('updated_at as updated_at'))
    	->groupBy('hour')
    	->orderBy('updated_at', 'ASC')
    	->get();


    	foreach($npcMetrics as $dailyGraph) {

    		$regionDaily[$dailyGraph->updated_at] = $dailyGraph->npc_kills;
    	}

    	return $regionDaily;

    }

    public function regionMetrics48Hours($region_id) {

    	$regionDaily  = array();


    	$npcMetrics = DB::table('npc_kills')
    	->where('region_id', $region_id)
        //->where('created_at', '>=', Carbon::now()->subDay())
    	->whereBetween('created_at', [Carbon::now()->subDay(2),Carbon::now()->subDay()])
    	->select(DB::raw('DATE(created_at) as date'), DB::raw('HOUR(created_at) as hour'), DB::raw('sum(npc_kills) as npc_kills'), DB::raw('updated_at as updated_at'))
    	->groupBy('hour')
    	->orderBy('updated_at', 'ASC')
    	->get();

    	foreach($npcMetrics as $dailyGraph) {

    		$regionDaily[$dailyGraph->updated_at] = $dailyGraph->npc_kills;
    	}

    	return $regionDaily;

    }


    public function allRegionMetrics() {

    	$allRegionsByDay  = array();

    	$npcMetrics = DB::table('npc_kills')
    	->where('created_at', '>=', Carbon::now()->subDay())
    	->select(DB::raw('DATE(created_at) as date'), DB::raw('DAY(created_at) as day'), DB::raw('sum(npc_kills) as npc_kills'), DB::raw('region_id as region_id'))
    	->groupBy('region_id')
    	//->orderBy('updated_at', 'ASC')
    	->get();

    	foreach($npcMetrics as $dailyGraph) {

    		$regionName = $this->getRegionName($dailyGraph->region_id);

    		$allRegionsByDay[$regionName] = $dailyGraph->npc_kills;
    	}

    	return $allRegionsByDay;

    }


    public function getSolarSystem($system_id)
    {

    	$system = SolarSystems::where('ss_system_id', $system_id)
    	->first();

    	return $system['ss_system_name'];

    }

    public function getRegionName($region_id)
    {

    	if($region_id == 0) {
    		return "Orphen";
    	}  else {


        // Get Ledger for User

    		$region = SolarSystems::where('ss_region_id', $region_id)
    		->first();
    	}

    	return $region['ss_region_name'];

    }
}
