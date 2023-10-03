<?php

namespace Vanguard\Console\Commands\TouchedMyCitadel;

use Illuminate\Console\Command;

use Vanguard\KnownStructures;
use Vanguard\ActivityTracker;
use Vanguard\Alliances;
use Vanguard\Corporations;

class getStructureActivityLogIntel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'citadel:touched';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Touched My Citadel, Acitivty Log/Citadel Search';

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
        ## Makes a Family Tree, Yeahh
    	## Find all the times in the activity tracker that Lowlife. owned a structure
    	$logs = ActivityTracker::where('at_action', 'Structure Belongs to Lowlife.')
    	->orWhere('at_action', 'Structure Belongs to Adversity.')
    	->orWhere('at_action', 'Structure Belongs to Wife Is Sleeping')
    	->orWhere('at_action', 'Structure Belongs to BBC Holdings')
    	->orWhere('at_action', 'Structure Belongs to Nothing Comes To Mind')
    	->orWhere('at_action', 'Structure Belongs to RICARD0')
        //->groupBy('at_structure_id') # Maybe do this, check dataset.
    	->get();

    	$this->info('Found ' . count($logs) . ' Structures, Using Wizard Wand.. Standby.');

        ## For each of these structures, who else touched it. 

    	foreach($logs as $touched) {
        	## Cycle each log, pluck the structure id and give me a list of who else touched it.

    		$i_also_touched_it = ActivityTracker::where('at_structure_id', $touched->at_structure_id)
    		->where('at_action', 'like', 'Structure Belongs%')
    		->groupBy('at_action')
    		->get();

    		if(count($i_also_touched_it) > 1) {

    			foreach($i_also_touched_it as $an_other_corporation) {
        		## I touched it too.
        		# Get the structure name, just incase we don't have it.
    				$structure_name = KnownStructures::where('str_structure_id_md5', $an_other_corporation->at_structure_hash)->first();

    				$this->info('Structure Name: ' . $structure_name->str_name . ' | Type: ' . $structure_name->str_type . ' | Ownership: ' . $an_other_corporation->at_action . ' | Dated: ' . $an_other_corporation->created_at);
    			}
    			
    		}
    	}

    }
}
