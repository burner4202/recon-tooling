<?php

namespace Vanguard\Console\Commands\Coordination;

use Illuminate\Console\Command;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

use Vanguard\CurrentlyClockedIn;

class getFleetsCheckedInCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fleets:checkedin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Checked In Fleets';

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

    	# Grab our API key from ENV.
    	$secret_key = config('adash.secret_key');

    	## Kill the fleets activity before we pull the data again.

    	CurrentlyClockedIn::where('active', 1)->update(['active' => 0]);

    	# Make an API Call.
    	# What fleets are out.

    	try {
    		$client = new Client([
    			'headers' => ['aD_APISecret' => $secret_key]
    		]);

    		$response = $client->request(
    			'GET',
    			'https://adashboard.info/recon/api/fleet/currentclockedin',
    			['aD_APISecret' => $secret_key]
    		);

    		$data = $response->getBody()->getContents();

    		$fleets = json_decode($data, true);

    		## We found fleets, lets get the info.

    		foreach ($fleets as $fleet) {

    			//$this->info(key($fleet));

    			$fleet_id = key($fleet);

    			$fleet_comp = $this->getFleetInfo($fleet_id);

    			$boss = $fleet_comp[0]->boss;
    			$owner = $fleet_comp[1]->owner;
    			$size = $fleet_comp[2]->size;
    			$free_move = $fleet_comp[4]->freeMove;

    			if($free_move) {
    				$freemove = "Yes";
    			} else {
    				$freemove = "No";
    			}

    			$registered = $fleet_comp[5]->registered;

    			if($registered) {
    				$advert = "Yes";
    			} else {
    				$advert = "No";
    			}

    			$location = $fleet_comp[7]->location;
    			$ship = $fleet_comp[8]->ships;

    			$this->info(\Carbon\Carbon::now());

    			$this->info('Fleet: ' . $fleet_id . ' Owner: ' . $owner . ' Boss: ' . $boss . ' Free Move: ' . $freemove . ' Registered: ' . $advert);
    			$this->info($size . ' pilots in this fleet.');
    			$this->info('Reading Systems...');

    			//print_r($location);


    			$system_array = array();


    			if(count($location) > 0) {

    				foreach($location as $where) {

    					$system = key($where);
    					$no_of_pilots_in_system = $where->$system;

    					$system_array[] = $system . ' (' . $no_of_pilots_in_system . ')';

    					$this->info('System: ' . $system . ' has ' . $no_of_pilots_in_system . ' pilots in system.');
    				}
    			}

    			$ship_array = array();

    			if(count($ship) > 0) {

    				$this->info('Reading Fleet...');
    				
    				foreach($ship as $type) {
    					$hull_name = key($type);
    					$no_of_hulls = $type->$hull_name;
    					$this->info($hull_name . ' (' . $no_of_hulls . ')');

    					$ship_array[] = $hull_name . ' (' . $no_of_hulls . ')';
    				}
    			}

    			$system_string = implode(', ', $system_array);
    			$ship_string = implode(', ', $ship_array);

    			$this->info($system_string);
    			$this->info($ship_string);

    			
    			$update = CurrentlyClockedIn::updateOrCreate([
    				'fleet_id'                                   => $fleet_id,
    			],[
    				'fleet_owner'                  	  		     => $owner,
    				'fleet_boss'                    			 => $boss,
    				'fleet_size'                    			 => $size,
    				'freemove'           					     => $freemove,
    				'advert'            					     => $advert,
    				'system_numbers'                       		 => $system_string,
    				'hull_numbers'                   			 => $ship_string,
    				'active'                       				 => 1,
    			]);
    			


    			/*

    			  0 => {#2278
				    +"boss": "kocicek"
				  }
				  1 => {#2276
				    +"owner": "kocicek"
				  }
				  2 => {#2265
				    +"size": 1
				  }
				  3 => {#2274
				    +"voice": false
				  }
				  4 => {#2268
				    +"freeMove": false
				  }
				  5 => {#2267
				    +"registered": false
				  }
				  6 => {#2264
				    +"motd": null
				  }
				  7 => {#2277
				    +"location": []
				  }
				  8 => {#2272
				    +"ships": []
				  }
				]

				*/


			}

		} catch (RequestException $e) {
    		//echo Psr7\str($e->getRequest());
			if ($e->hasResponse()) {
				$this->info("No Fleets Out");

				# Mark all the fleets as home.
				CurrentlyClockedIn::where('active', 1)->update(['active' => 0]);


			}
		}

	}

	public function getFleetInfo($fleet_id) {

		# Grab our API key from ENV.
		$secret_key = config('adash.secret_key');

		$client = new Client([
			'headers' => ['aD_APISecret' => $secret_key]
		]);

		$response = $client->request(
			'GET',
			'https://adashboard.info/recon/api/fleet/' . $fleet_id,
			['aD_APISecret' => $secret_key]
		);

		return json_decode($response->getBody()->getContents());
	}
}
