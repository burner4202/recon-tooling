<?php

namespace Vanguard\Console\Commands\Coordination;

use Illuminate\Console\Command;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

use Vanguard\WatchedSystems;
use Vanguard\WatchedSystemsDscan;

class getWatchedSystemsDscanCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'watched:systems:dscan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets all the data from the watched systems. (Dscan)';

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


        # Make an API Call.
        # What systems are being watched.

        $watched_systems = WatchedSystems::orderBy('solar_system_name')->get();

        # We have systems.
        if($watched_systems) {

            # Cycle Each System & Update
            foreach($watched_systems as $systems) {

               $dscans = $this->get_system_dscan($systems->solar_system_id);

               $ship_array = array();

               if($dscans) {

                # I only want the first, so reverse it.
                $remap_dscan = array_reverse($dscans);

                $adash_url = key($remap_dscan[0]);

                $this->info($adash_url);

                foreach($remap_dscan[0] as $ships) {

                    foreach($ships->ships as $type) {

                       # The ships are here, we should build an array

                        # Ship Type
                        # Count

                        $ship_type = key($type);

                        # Get the ship count
                        foreach($type as $seen) {

                            $ship_count = $seen[0]->seen;
                        }

                        $ship_array[] = $ship_type . ' (' . $ship_count . ')';


                    }

                }


                # Update Local Scans
                $ship_string = implode(', ', $ship_array);

                $this->info('https://adashboard.info/intel/dscan/view/'.$adash_url);
                $this->info($ship_string);

                $adash = 'https://adashboard.info/intel/dscan/view/'.$adash_url;

                # Only update if the ship array has values

                if($ship_array) {
                    $update = WatchedSystemsDscan::updateOrCreate([
                        'solar_system_id'                             => $systems->solar_system_id,
                    ],[
                        'solar_system_name'                           => $systems->solar_system_name,
                        'constellation_id'                            => $systems->constellation_id,
                        'constellation_name'                          => $systems->constellation_name,
                        'region_id'                                   => $systems->region_id,
                        'region_name'                                 => $systems->region_name,
                        'adash_url'                                   => $adash,
                        'dscan'                                       => $ship_string,

                    ]);  
                }
                
            }


            # END FOREACH


        }

        # END IF
    }

}

public function get_system_dscan($system_id) {

    /*
    /recon/api/ds/recentfromsystem/<system id> shows the dscans in that system (last minute), ordered by most recent, limited to about 6 i think, output is json
    oh some details: dscans only show 25 ships if more than 5 on dscan (as not to clutter you too much with random ibusses and frigates)
    */

    # Grab our API key from ENV.
    $secret_key = config('adash.secret_key');

    try {
        $client = new Client([
            'headers' => ['aD_APISecret' => $secret_key]
        ]);

        $response = $client->request(
            'GET',
            'https://adashboard.info/recon/api/ds/recentfromsystem/' . $system_id,
            ['aD_APISecret' => $secret_key]
        );

        return json_decode($response->getBody()->getContents());

    } catch (RequestException $e) {
            //echo Psr7\str($e->getRequest());
        if ($e->hasResponse()) {
            $this->info($e);
        }
    }


}
}
