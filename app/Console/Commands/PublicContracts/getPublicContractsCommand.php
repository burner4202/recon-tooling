<?php

namespace Vanguard\Console\Commands\PublicContracts;

use Illuminate\Console\Command;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use Vanguard\PublicContracts;
use Vanguard\AllianceStandings;
use Vanguard\Corporation;
use Vanguard\Alliances;
use Vanguard\SolarSystems;
use Carbon\Carbon;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;


class getPublicContractsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contracts:get:public';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get public contracts for Delve/Fountain/Querious/Peroid Basis';

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

    	$client_id = config('eve.client_id');
    	$secret_key = config('eve.secret_key');
    	$esi = new Eseye();

        # Hardcoded Regions for Contracts we want to pull.
    	$regions = [
            10000060, # Delve
            10000050, # Querious
            10000058, # Fountain
            10000063, # Period Basis
        ];

        foreach($regions as $region_id) {

        	$this->info('Checking Region: ' . $region_id);

        	$region = SolarSystems::where('ss_region_id', $region_id)->first();

            # Guzzle Client
            # Get Request to: https://esi.evetech.net/ui/#/Contracts/get_contracts_public_region_id
        	$client = new Client();
        	$response = $client->request('GET', 'https://esi.evetech.net/latest/contracts/public/' . $region_id . '/?datasource=tranquility');

            # Check Headers for Page Count.
        	$no_of_pages = $response->getHeader('X-Pages')[0];

            # While Loop to go through each page and query the data.
        	$x = 1;
        	while($x <= $no_of_pages) {

                # Get the contracts on this page.

        		$request = json_decode(@file_get_contents('https://esi.evetech.net/latest/contracts/public/' . $region_id . '/?datasource=tranquility&page=' . $x), true);

        		if($request) {

        			foreach ($request as $contract) {

                        # Consider adding caching here for the contract to speed things up.
                        # $this->info('Checking Contract : ' . $contract['contract_id']);



        				$contract_id = $contract['contract_id'];

        				$does_it_exist = PublicContracts::where('contract_id', $contract_id)->first();


        				if($contract['type'] == "item_exchange" && !$does_it_exist) {

                        ## Does the contract already exist

                        # We should query the contract data and check if it is a Capital.

        					$items = json_decode(@file_get_contents('https://esi.evetech.net/latest/contracts/public/items/' . $contract_id .'/?datasource=tranquility&page=1'), true);

        					if($items) {

                        # Cycle Each Item and Check if it contains a capital.

        						foreach ($items as $item) {

        							if($this->iWantThisHull($item['type_id'])) {

        								$this->info('Found a Hull!' . ' : ' . $contract['contract_id'] . ' : Item ID: ' . $item['type_id']);

        								$type_id = json_decode(@file_get_contents('https://esi.evetech.net/latest/universe/types/' . $item['type_id'] .'/?datasource=tranquility'), true);

                                    # Save to Database.

        								$character = $esi->invoke('get', '/characters/{character_id}/', [
        									'character_id' => $contract['issuer_id'],
        								]);

        								if(isset($character->name)) {

        									$character_name = $character->name;
        									$corporation_id = $character->corporation_id;

        									$corporation = $esi->invoke('get', '/corporations/{corporation_id}/', [
        										'corporation_id' =>  $character->corporation_id,
        									]);

        									$corporation_name = $corporation->name;

        									if(isset($corporation->alliance_id)) {

        										$alliance = Alliances::where('alliance_alliance_id', $corporation->alliance_id)->first();
        										$alliance_id = $corporation->alliance_id;
        										$alliance_name = $alliance->alliance_name;

        									} else {

        										$alliance_name = "";
        										$alliance_id = "";
        									}

        								} else {

        									$character_name = "";
        									$corporation_id = $contract['issuer_id'];

        									$corporation = $esi->invoke('get', '/corporations/{corporation_id}/', [
        										'corporation_id' => $corporation_id,
        									]);

        									$corporation_name = $corporation->name;

        									if(isset($corporation->alliance_id)) {

        										$alliance = Alliances::where('alliance_alliance_id', $corporation->alliance_id)->first();
        										$alliance_id = $corporation->alliance_id;
        										$alliance_name = $alliance->alliance_name;

        									} else {

        										$alliance_name = "";
        										$alliance_id = "";
        									}
        								}

        								$standing = AllianceStandings::where('as_contact_id', $contract['issuer_id'])
        								->orWhere('as_contact_id', $corporation_id)
        								->orWhere('as_contact_id', $alliance_id)
        								->first();

        								if($standing) {
        									$standings = $standing->as_standing;
        								} else {
        									$standings = 0;
        								}

                                    # Hull Mapping
        								$is_titan = 0;
        								$is_super = 0;
        								$is_carrier = 0;
        								$is_fax = 0;
        								$is_dread = 0;
        								$is_npc_delve = 0;

        								if($this->checkIfTitan($item['type_id'])) { $is_titan = 1; }
        								if($this->checkIfSuper($item['type_id'])) { $is_super = 1; }
        								if($this->checkIfCarrier($item['type_id'])) { $is_carrier = 1; }
        								if($this->checkIfFax($item['type_id'])) { $is_fax = 1; }
        								if($this->checkifDread($item['type_id'])) { $is_dread = 1; }
        								if($this->isNPCDelve($contract['start_location_id'])) { $is_npc_delve = 1; }

        								$date_issued = $this->formatEveDate($contract['date_issued']);
        								$date_expired = $this->formatEveDate($contract['date_expired']);
        								$showinfo_link = '<url=contract:30003576//' . $contract['contract_id'] . '>' . $region->ss_region_name . ' : ' . $type_id['name'] . '</url>';

        								$contract = PublicContracts::updateOrCreate([
        									'contract_id'                       => $contract['contract_id']
        								],[
        									'type_id'                           => $item['type_id'],
        									'type_name'                         => $type_id['name'],
        									'region_id'                         => $region_id,
        									'region_name'                       => $region->ss_region_name,
        									'price'                             => $contract['price'],
        									'date_issued'                       => $date_issued,
        									'date_expired'                      => $date_expired,
        									'issuer_id'                         => $contract['issuer_id'],
        									'character_name'                    => $character_name,
        									'corporation_id'                    => $corporation_id,
        									'corporation_name'                  => $corporation_name,
        									'alliance_id'                       => $alliance_id,
        									'alliance_name'                     => $alliance_name,
        									'showinfo_link'                     => $showinfo_link,
        									'is_carrier'                        => $is_carrier,
        									'is_fax'                            => $is_fax,
        									'is_dread'                          => $is_dread,
        									'is_super'                          => $is_super,
        									'is_titan'                          => $is_titan,
        									'is_npc_delve'                      => $is_npc_delve,
        									'contract_info'                     => json_encode($contract),
        									'standing'                          => $standings,

        								]);

        								if($standings > 0) {
        									$contract_standing = "FRIENDLY";
        								} elseif($standings == 0.00){
        									$contract_standing = "NEUTRAL";
        								} else {
        									$contract_standing = "HOSTILE";
        								}

        								if($is_carrier == 1) {
        									$hull_type = "Carrier";
        								}

        								if($is_fax == 1) {
        									$hull_type = "Fax";
        								}

        								if($is_dread == 1) {
        									$hull_type = "Dread";
        								}

        								if($is_super == 1) {
        									$hull_type = "Super";
        								}

        								if($is_titan == 1) {
        									$hull_type = "Titan";
        								}

        								if($is_npc_delve == 1) {
        									$content = $contract_standing . ' : ' . $type_id['name'] .' (' . $hull_type . ')' . ' Hull Found in NPC Delve belonging to ' . $character_name . ' of ' . $corporation_name . ' (' . $alliance_name . ') : Ingame Link : ' . $showinfo_link; 
        								} else {
        									$content = $contract_standing . ' : ' . $type_id['name'] .' (' . $hull_type . ')' . ' Hull Found in ' . $region->ss_region_name . ' belonging to ' . $character_name . ' of ' . $corporation_name . ' (' . $alliance_name . ') : Ingame Link : ' . $showinfo_link; 
        								} 

        								$this->postToJabber($content);

        								# Diplo Request to post to diplo_tv
        								# Only post Blue Supers & Titans

        								# Blue Standings
        								if($standings > 0) {

        									# Check if it is a super or titan

        									if($is_super == 1 || $is_titan == 1) {

        										# Send it to Jabber. diplo_tv 
        										# Do not send Goonswarm ones.

        										//if ($alliance_name != "Goonswarm Federation") {

        											$content = $contract_standing . ' : ' . $type_id['name'] .' (' . $hull_type . ')' . ' Hull Found in ' . $region->ss_region_name . ' belonging to ' . $character_name . ' of ' . $corporation_name . ' (' . $alliance_name . ') : Ingame Link : ' . $showinfo_link; 

        											$this->postToJabberDiplo($content);
        										//}


        									}



        								}


        							}


                                #END FOREACH
        						}   

                            #END IF
        					}

                        #END FOREACH
        				}

                    #END IF
        			}

                # Increase Page Count
        			$x++;

        		}

            #END WHILE

        	}

        }

        # END FOREACH

    }



    public function postToJabber($content) {

    	$channel = 'sales-report@conference.goonfleet.com';
        # I don't care about errors.
    	$client = new \GuzzleHttp\Client(['http_errors' => false]);

    	$options = [
    		'channel' => $channel,
    		'payload' => $content,
    	];

    	$url = "https://recon-bot.7rqtwti-zm2ubuph7uwzs.apps.gnf.lt/api/webhook";
    	$request = $client->post($url, ['body' => json_encode($options) ]);

    }

    public function postToJabberDiplo($content) {

    	$channel = 'diplo_tv@conference.goonfleet.com';
        # I don't care about errors.
    	$client = new \GuzzleHttp\Client(['http_errors' => false]);

    	$options = [
    		'channel' => $channel,
    		'payload' => $content,
    	];

    	$url = "https://recon-bot.7rqtwti-zm2ubuph7uwzs.apps.gnf.lt/api/webhook";
    	$request = $client->post($url, ['body' => json_encode($options) ]);

    }

    public function formatEveDate($date) {
    	$trimmed = rtrim($date, "Z");
    	$dateAndTime = explode("T", $trimmed);
    	$dt = Carbon::parse($dateAndTime[0] . " " . $dateAndTime[1]);   
    	return $dt;   
    }


    /**
    * Looking for a Titan/Super/Carrier Hull.
    *
    * @return boolean
    */  
    public function iWantThisHull($type_id) {

    	$hulls = [
            #Titans

            11567,   # Avatar
            3764,    # Levi
            671,     # Bus
            23773,   # Scrapmetal (Rag)

            # Faction Titans

            45649,   # Tanky Bitch (Komodo)
            42241,   # Molok, Heard INIT Sold One.
            42126,   # Vanquisher, I want one.

            # Supers

            23919,   # Aeon
            23917,   # Wyvern
            23913,   # Nyx
            22852,   # Hel

            # Carriers

            23757,   # Archon
            23915,   # Chimera
            23911,   # Thanatos
            24483,   # Nidhoggur

            # Faction Carriers

            3514,    # Revenant, I'm gay for Jay.
            42125,   # V for Vendetta

            # FAX

            37604,   # Apostle
            37605,   # Minokawa
            37607,   # Ninazu
            37606,   # Lif
            42242,   # Dagon
            45645,   # Loggerhead

            # Dreads

            19720,   # Revelation
            19726,   # Phoenix
            19724,   # Moros
            19722,   # Naglfar
            52907,   # Zirnitra

            # Faction Dreads 

            45746,   # Caiman
            42243,   # Chemosh
            42124,   # Vehement

        ];


        if (in_array($type_id, $hulls)) {
        	return true;
        } else {
        	return false;
        }
    }

    /**
    * Titan Check
    *
    * @return boolean
    */  
    public function checkIfTitan($type_id) {

    	$hulls = [
            #Titans

            11567,   # Avatar
            3764,    # Levi
            671,     # Bus
            23773,   # Scrapmetal (Rag)
            45649,   # Tanky Bitch (Komodo)
            42241,   # Molok, Heard INIT Sold One.
            42126,   # Vanquisher, I want one

        ];


        if (in_array($type_id, $hulls)) {
        	return true;
        } else {
        	return false;
        }
    }

    /**
    * Looking for a Super
    *
    * @return boolean
    */  
    public function checkIfSuper($type_id) {

    	$hulls = [
            # Supers

            23919,   # Aeon
            23917,   # Wyvern
            23913,   # Nyx
            22852,   # Hel
            3514,    # Revenant, I'm gay for Jay.
            42125,   # V for Vendetta

        ];


        if (in_array($type_id, $hulls)) {
        	return true;
        } else {
        	return false;
        }
    }

    /**
    * Looking for a Carrier
    *
    * @return boolean
    */  
    public function checkIfCarrier($type_id) {

    	$hulls = [
            # Carriers

            23757,   # Archon
            23915,   # Chimera
            23911,   # Thanatos
            24483,   # Nidhoggur
        ];


        if (in_array($type_id, $hulls)) {
        	return true;
        } else {
        	return false;
        }
    }

    /**
    * Looking for a Fax
    *
    * @return boolean
    */  
    public function checkIfFax($type_id) {

    	$hulls = [
            # FAX

            37604,   # Apostle
            37605,   # Minokawa
            37607,   # Ninazu
            37606,   # Lif
            42242,   # Dagon
            45645,   # Loggerhead

        ];


        if (in_array($type_id, $hulls)) {
        	return true;
        } else {
        	return false;
        }
    }

    /**
    * Looking for a Dread
    *
    * @return boolean
    */  
    public function checkifDread($type_id) {

    	$hulls = [
            # Dreads

            19720,   # Revelation
            19726,   # Phoenix
            19724,   # Moros
            19722,   # Naglfar
            45746,   # Caiman
            42243,   # Chemosh
            42124,   # Vehement
            52907,   # Zirnitra

        ];


        if (in_array($type_id, $hulls)) {
        	return true;
        } else {
        	return false;
        }
    }

    /**
    * is NPC Delve
    *
    * @return boolean
    */  
    public function isNPCDelve($station_id) {

    	$stations = [
    		60014941,
    		60014942,
    		60014943,
    		60014944,
    		60014945,
    		60014946,
    		60014947,
    		60014948,
    		60014949,
    		60014950,
    		60014951,
    		60014952,
    	];


    	if (in_array($station_id, $stations)) {
    		return true;
    	} else {
    		return false;
    	}
    }

}
