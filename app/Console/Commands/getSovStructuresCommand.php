<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;
use Vanguard\SolarSystems;
use Vanguard\Alliances;
use Vanguard\SovStructures;
use Vanguard\KnownStructures;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class getSovStructuresCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:getSovStructures';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets Sov Structures and Updates Database';

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
    	# Clean Table Before we Start.
    	SovStructures::truncate();

    	# Query CCP for the goodies.
    	$sov_structures = $this->getSovStructures();

    	$bar = $this->output->createProgressBar(count($sov_structures));
    	$bar->start();

    	foreach($sov_structures as $meh) {

    		$alliance = Alliances::where('alliance_alliance_id', $meh->alliance_id)->first();
    		$system = SolarSystems::where('ss_system_id', $meh->solar_system_id)->first();
            $super_production = KnownStructures::where('str_system_id', $meh->solar_system_id)
            ->where('str_supercapital_shipyard', 1)
            ->where('str_destroyed', 0)
            ->first();

            $jump_bridge = KnownStructures::where('str_system_id', $meh->solar_system_id)
            ->where('str_type', 'Ansiblex Jump Gate')
            ->where('str_destroyed', 0)
            ->first();

            $keepstar = KnownStructures::where('str_system_id', $meh->solar_system_id)
            ->where('str_type', 'Keepstar')
            ->where('str_destroyed', 0)
            ->first();


    		$structure_type = $this->StructureType($meh->structure_type_id);

    		# Because CCP is bad, set a fucking value.
    		if(isset($meh->vulnerable_end_time)) {
    			$vulnerable_end_time = $this->getDateTime($meh->vulnerable_end_time);
    		} else {
    			$vulnerable_end_time = "";
    		}

    		if(isset($meh->vulnerable_start_time)) {
    			$vulnerable_start_time = $this->getDateTime($meh->vulnerable_start_time);
    		} else {
    			$vulnerable_start_time = "";
    		}

    		if(isset($meh->vulnerability_occupancy_level)) {
    			$vulnerability_occupancy_level = $meh->vulnerability_occupancy_level; 
    		} else {
    			$vulnerability_occupancy_level = "";
    		}

            if(isset($super_production)) {
                $supers_in_system = 1;
            } else {
                $supers_in_system = 0;
            }

            if(isset($jump_bridge)) {
                $bridge_in_system = 1;
            } else {
                $bridge_in_system = 0;
            }

                        if(isset($keepstar)) {
                $keepstar_in_system = 1;
            } else {
                $keepstar_in_system = 0;
            }

    		$sov_structure_key = $meh->solar_system_id . "+" . $meh->structure_type_id;

    		$update = SovStructures::updateOrCreate([
    			'sov_structure_key'              => $sov_structure_key,
    		],[
    			'alliance_id'                    => $meh->alliance_id,
    			'alliance_name'                  => $alliance->alliance_name,
    			'alliance_ticker'				 => $alliance->alliance_ticker,
    			'solar_system_id'                => $system->ss_system_id,
    			'solar_system_name'              => $system->ss_system_name,
    			'constellation_id'               => $system->ss_constellation_id,
    			'constellation_name'             => $system->ss_constellation_name,
    			'region_id'               		 => $system->ss_region_id,
    			'region_name'             		 => $system->ss_region_name,
    			'structure_type_id'              => $meh->structure_type_id,
    			'structure_type_name'            => $structure_type,
    			'vulnerability_occupancy_level'	 => $vulnerability_occupancy_level,
    			'vulnerable_end_time'			 => $vulnerable_end_time,
    			'vulnerable_start_time'			 => $vulnerable_start_time,
                'supers_in_system'               => $supers_in_system,
                'bridge_in_system'               => $bridge_in_system,
                'keepstar_in_system'             => $keepstar_in_system,

    		]);

    		$bar->advance();
    	}


        # Lets Calculate the Health.

        $this->call('alliances:health');


    	$bar->finish();


    }

    public function getSovStructures() {

    	$configuration = Configuration::getInstance();

    	$client_id = config('eve.client_id');
    	$secret_key = config('eve.secret_key');

    	try {

    		$esi = new Eseye();
    		$response = $esi->invoke('get', '/sovereignty/structures/', []);


    	}  catch (EsiScopeAccessDeniedException $e) {

    		$this->error('SSO Token is invalid');

    	} catch (RequestFailedException $e) {

    		$this->error('Got an ESI Error');

    	} catch (Exception $e) {

    		$this->error('ESI is fucked');
    	}

    	return $response;

    }

    public function getDateTime($string) {
    	$trimmed = rtrim($string, "Z");
    	$dateAndTime = explode("T", $trimmed);
    	$dt = Carbon::parse($dateAndTime[0] . " " . $dateAndTime[1]);   
    	return $dt;   
    }

    public function StructureType($type_id) {
    	if($type_id == 32226) {
    		return "Territorial Claim Unit";
    	}

    	if($type_id == 32458) {
    		return "Infrastructure Hub";
    	}

    	return "";
    }

}

