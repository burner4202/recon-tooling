<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Datetime;

use Auth;
use Log;
use Carbon\Carbon;
use Vanguard\User;
use Vanguard\SolarSystems;
use Vanguard\NPCKills;
use Vanguard\TypeIDs;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class getSystemNPCKills implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $configuration = Configuration::getInstance();

        $client_id = config('eve.client_id');
        $secret_key = config('eve.secret_key');

        $esi = new Eseye();


        try { 

            $system_kills = $esi->invoke('get', '/universe/system_kills/', [   
            ]);
            //$this->info('Found : ' . count($system_kills) . ' systems with activity. Adding to database...');
            //$bar = $this->output->createProgressBar(count($system_kills));



            foreach ($system_kills as $system) {

                if(!isset($systemDetails->constellation_id)) { $constellation = ""; } else { $constellation = $systemDetails->constellation_id; }
                if(!isset($systemDetails->region_id)) { $region = ""; } else { $region = $systemDetails->region_id; }


                $systemDetails = $this->getSolarSystemInfo($system->system_id);

                $npckill_id = md5($system->system_id . $system_kills->headers['Expires']);

                $insertIntoDB = NPCKills::updateOrCreate([
                    'npc_kill_id'           => $npckill_id,
                ],[
                    'solar_system_id'       => $system->system_id,
                    'constellation_id'      => $constellation,
                    'region_id'             => $region,
                    //'solar_system_name'       => $systemDetails->system_name,
                    //'constellation_name'  => $systemDetails->constellation_name,
                    //'region_name'         => $systemDetails->region_name,
                    'npc_kills'             => $system->npc_kills,

                ]);

                //$bar->advance();

            }

        } catch (EsiScopeAccessDeniedException $e) {

            $this->error('SSO Token is invalid');

        } catch (RequestFailedException $e) {

            $this->error('Got an ESI Error');

        } catch (Exception $e) {

            $this->error('ESI is fucked');
        }


        //$bar->finish();
    }




    public function getSolarSystemInfo($system_id) {

        $system = SolarSystems::where('system_id', $system_id)
        ->first();

        return $system;
    }
}


