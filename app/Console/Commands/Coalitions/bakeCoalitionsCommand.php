<?php

namespace Vanguard\Console\Commands\Coalitions;

use Illuminate\Console\Command;

use Vanguard\Coalitions;
use Vanguard\CoalitionsBake;
use Vanguard\Corporations;
use Vanguard\Alliances;


class bakeCoalitionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:bakeCoalitions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bake Coalitions';

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
    	# Get the Coalitions
    	$coalitions = Coalitions::orderBy('id')->get();

        # Purge the Bake
    	CoalitionsBake::truncate();

        # Update each Coalition

    	foreach ($coalitions as $coalition) {

        	# Got the Coalition, Lets get the Alliances

    		$alliances = Alliances::where('alliance_coalition', $coalition->id)->get();

    		foreach ($alliances as $alliance) {

        		# Got the alliances, lets grab the corporations

    			$corporations = Corporations::where('corporation_alliance_id', $alliance->alliance_alliance_id)->get();

    			foreach ($corporations as $corporation) {


    				$insert = new CoalitionsBake;

    				$insert->coalition_id 					= $coalition->id;
    				$insert->coalition_name 				= $coalition->name;
    				$insert->corporation_id 				= $corporation->corporation_corporation_id;
    				$insert->corporation_name 				= $corporation->corporation_name;
    				$insert->corporation_member_count 		= $corporation->corporation_member_count;
    				$insert->alliance_id 					= $alliance->alliance_alliance_id;
    				$insert->alliance_name 					= $alliance->alliance_name;
    				$insert->alliance_ticker 				= $alliance->alliance_ticker;
    				$insert->save();


    			}
    		}
    	}
    }
}

