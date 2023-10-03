<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;
use Vanguard\SolarSystems;
use Vanguard\SystemCostIndices;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class getSystemCostIndices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:getSystemCostIndices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets System Indexes and Updates Database';

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
    	$indices = $this->getIndices();

    	$now = Carbon::now();
    	$yesterday = Carbon::now()->subDay(1);

    	$bar = $this->output->createProgressBar(count($indices));
    	$bar->start();

    	foreach($indices as $system_indices) {

    		$solar_system_id = $system_indices->solar_system_id;
    		$system = SolarSystems::where('ss_system_id', $solar_system_id)->first();

    		$indexes = $system_indices->cost_indices;
    		$manufacturing = $indexes['0']->cost_index;
    		$researching_time_efficiency = $indexes['1']->cost_index;
    		$researching_material_efficiency = $indexes['2']->cost_index;
    		$copying = $indexes['3']->cost_index;
    		$invention = $indexes['4']->cost_index;
    		$reaction = $indexes['5']->cost_index;
    		$key = $solar_system_id . '-' .  $now->format('Y-m-d');


    			/*
    			  0 => {#39088
				    +"activity": "manufacturing"
				    +"cost_index": 0.001
				  }
				  1 => {#39089
				    +"activity": "researching_time_efficiency"
				    +"cost_index": 0.001
				  }
				  2 => {#39090
				    +"activity": "researching_material_efficiency"
				    +"cost_index": 0.001
				  }
				  3 => {#39091
				    +"activity": "copying"
				    +"cost_index": 0.001
				  }
				  4 => {#39092
				    +"activity": "invention"
				    +"cost_index": 0.001
				  }
				  5 => {#39093
				    +"activity": "reaction"
				    +"cost_index": 0.001
				  }

				*/


				# Calculate the Delta.

    			# (final / initial) * 100

				  $inital = SystemCostIndices::where('sci_solar_system_id', $solar_system_id)
				  ->where('sci_date', $yesterday->format('Y-m-d'))
				  ->first();

				  if($inital) {

					# Found yesterdays records, lets calculate the delta

				  	$sci_manufacturing_delta 						= (($manufacturing - $inital->sci_manufacturing) / $inital->sci_manufacturing * 100);
				  	$sci_researching_time_efficiency_delta 			= (($researching_time_efficiency - $inital->sci_researching_time_efficiency) / $inital->sci_researching_time_efficiency * 100);
				  	$sci_researching_material_efficiency_delta 		= (($researching_material_efficiency - $inital->sci_researching_material_efficiency) / $inital->sci_researching_material_efficiency * 100);
				  	$sci_copying_delta								= (($copying - $inital->sci_copying) / $inital->sci_copying * 100);
				  	$sci_invention_delta							= (($invention - $inital->sci_invention) / $inital->sci_invention * 100);
				  	$sci_reaction_delta								= (($reaction - $inital->sci_reaction) / $inital->sci_reaction * 100);

				  	//$this->info($manufacturing);
				  	//$this->info($inital->sci_manufacturing);

				  }	else {

				  	$sci_manufacturing_delta = 0;
				  	$sci_researching_time_efficiency_delta = 0;
				  	$sci_researching_material_efficiency_delta = 0;
				  	$sci_copying_delta = 0;
				  	$sci_invention_delta = 0;
				  	$sci_reaction_delta = 0;
				  }

				  //$this->info($sci_manufacturing_delta);




				  $update = SystemCostIndices::updateOrCreate([
				  	'sci_key'                                    => $key,
				  ],[
				  	'sci_solar_system_id'                        => $system->ss_system_id,
				  	'sci_solar_system_name'                      => $system->ss_system_name,
				  	'sci_solar_constellation_id'                 => $system->ss_constellation_id,
				  	'sci_solar_constellation_name'               => $system->ss_constellation_name,
				  	'sci_solar_region_id'                        => $system->ss_region_id,
				  	'sci_solar_region_name'                      => $system->ss_region_name,
				  	'sci_manufacturing'                          => $manufacturing,
				  	'sci_researching_time_efficiency'            => $researching_time_efficiency,
				  	'sci_researching_material_efficiency'        => $researching_material_efficiency,
				  	'sci_copying'                                => $copying,
				  	'sci_invention'                              => $invention,
				  	'sci_reaction'                               => $reaction,
				  	'sci_date'                                   => $now->format('Y-m-d'),
				  	'sci_security_status'                        => $system->ss_security_status,
				  	'sci_manufacturing_delta'					 => $sci_manufacturing_delta,
				  	'sci_researching_time_efficiency_delta'		 => $sci_researching_time_efficiency_delta,
				  	'sci_researching_material_efficiency_delta'  => $sci_researching_material_efficiency_delta,
				  	'sci_copying_delta'							 => $sci_copying_delta,
				  	'sci_invention_delta'						 => $sci_invention_delta,
				  	'sci_reaction_delta'						 => $sci_reaction_delta,


				  ]);



				  //$this->info(number_format($sci_manufacturing_delta));

				  $bar->advance();
				}



				$bar->finish();
			}

			public function getIndices() {

				$configuration = Configuration::getInstance();

				$client_id = config('eve.client_id');
				$secret_key = config('eve.secret_key');

				try {

					$esi = new Eseye();
					$response = $esi->invoke('get', '/industry/systems/', []);


				}  catch (EsiScopeAccessDeniedException $e) {

					$this->error('SSO Token is invalid');

				} catch (RequestFailedException $e) {

					$this->error('Got an ESI Error');

				} catch (Exception $e) {

					$this->error('ESI is fucked');
				}

				return $response;

			}

			public function postToJabber($content) {

				$channel = 'rt-dev@conference.goonfleet.com';
        		# I don't care about errors.
				$client = new \GuzzleHttp\Client(['http_errors' => false]);

				$options = [
					'channel' => $channel,
					'payload' => $content,
				];

				$url = "https://recon-bot.7rqtwti-zm2ubuph7uwzs.apps.gnf.lt/api/webhook";
				$request = $client->post($url, ['body' => json_encode($options) ]);

			}

		}
