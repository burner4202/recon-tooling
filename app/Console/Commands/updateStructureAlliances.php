<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;
use Vanguard\KnownStructures;
use Vanguard\Alliances;
use Vanguard\Corporations;
use Vanguard\SolarSystems;

class updateStructureAlliances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:updateStructureAlliances';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Quick hack instead of DB drop. - Update Alliances/Tickers/Regions for Structures';

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

    	$structures = KnownStructures::all();
    	$bar = $this->output->createProgressBar(count($structures));
    	$bar->start();

    	foreach($structures as $structure) {
    		$bar->advance();

    		$corporation = $structure->str_owner_corporation_id;

    		$system = SolarSystems::where('ss_system_id', $structure->str_system_id)->first();
    		
    		$update = KnownStructures::where('str_structure_id_md5', $structure->str_structure_id_md5)->first();
    		$update->str_region_id = $system->ss_region_id;
    		$update->str_region_name = $system->ss_region_name;
    		$update->str_constellation_id = $system->ss_constellation_id;
    		$update->str_constellation_name = $system->ss_constellation_name;
    		$update->save();

    		if($corporation > 0) {

    			$corporation = Corporations::where('corporation_corporation_id', $corporation)->first();
    			$alliance = Alliances::where('alliance_alliance_id', $corporation->corporation_alliance_id)->first();

    			if($corporation->corporation_alliance_id > 1) {

    				$update = KnownStructures::where('str_structure_id_md5', $structure->str_structure_id_md5)->first();
    				$update->str_owner_alliance_id = $alliance->alliance_alliance_id;
    				$update->str_owner_alliance_name = $alliance->alliance_name;
    				$update->str_owner_alliance_ticker = $alliance->alliance_ticker;
                    $post->timestamps = false;
    				$update->save();

    			}
    		}

    	}

    	$bar->finish();
    }
}

