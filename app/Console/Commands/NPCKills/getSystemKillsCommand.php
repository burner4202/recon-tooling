<?php

namespace Vanguard\Console\Commands\NPCKills;

use Illuminate\Console\Command;

use Datetime;

use Auth;
use Log;
use DB;
use Carbon\Carbon;
use Vanguard\User;
use Vanguard\SolarSystems;
use Vanguard\NPCKills;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class getSystemKillsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'npc:kills';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get System NPC Kills';

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

        ## Purge Database if older than 3 days.

    	DB::table('npc_kills')
    	->whereDate('created_at', '<', $now->subDay(7))
    	->delete();


    	try { 

    		$system_kills = collect(json_decode(file_get_contents('https://esi.evetech.net/latest/universe/system_kills/?datasource=tranquility')));
    		$this->info('Found : ' . count($system_kills) . ' systems with activity. Adding to database...');
    		$bar = $this->output->createProgressBar(count($system_kills));



    		foreach ($system_kills as $system) {

    			$systemDetails = $this->getSolarSystemInfo($system->system_id);

    			if(!isset($systemDetails->ss_constellation_id)) { $constellation = ""; } else { $constellation = $systemDetails->ss_constellation_id; }
    			if(!isset($systemDetails->ss_region_id)) { $region = ""; } else { $region = $systemDetails->ss_region_id; }
    			
    			$dt = Carbon::now()->minute(0)->second(0);

    			$npckill_id = ($system->system_id . "-" . $dt);

    			$insertIntoDB = NPCKills::updateOrCreate([
    				'npc_kill_id'           => $npckill_id,
    			],[
    				'solar_system_id'       => $system->system_id,
    				'constellation_id'      => $constellation,
    				'region_id'             => $region,
    				'solar_system_name'     => $systemDetails->ss_system_name,
    				'constellation_name'    => $systemDetails->ss_constellation_name,
    				'region_name'           => $systemDetails->ss_region_name,
    				'npc_kills'             => $system->npc_kills,

    			]);

    			$bar->advance();

    		}

    	} catch (Exception $e) {

    		$this->error('Failed');
    	}


    	$bar->finish();
    }




    public function getSolarSystemInfo($system_id) {

    	$system = SolarSystems::where('ss_system_id', $system_id)
    	->first();

    	return $system;
    }
}
