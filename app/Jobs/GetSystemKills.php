<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Queue;

use Datetime;

use Auth;
use Log;
use Vanguard\User;
use Vanguard\Characters; // EVE TOKEN INFORAMTION
use Vanguard\Character; // EVE ESI ENDPOINT INFORMATION
use Vanguard\CharactersForRecruitment; // Find New Recruits Model.
use Vanguard\LatestCharacterCheck;
use Vanguard\SystemKills;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Exception;
use Monolog\Logger;
use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\RequestFailedException;

use Carbon\Carbon;



class GetSystemKills implements ShouldQueue
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
        $client_id = config('eve.client_id');
        $secret_key = config('eve.secret_key');

    	$esi = new Eseye();

    	try { 

    		$response = $esi->invoke('get', '/universe/system_kills/', [
    		]);


    	} catch (EsiScopeAccessDeniedException $e) {

    		return ('Your ESI Token has been revoked, re-add it on the SSO page.');

    	} catch (RequestFailedException $e) {

    		return ('Got an ESI error');

    	} catch (Exception $e) {

    		return ('CCPs ESI is fucked.');
    	}

    	$dt = Carbon::now()->minute(0)->second(0);


    	if(isset($response)) {

    		foreach($response as $system_kill) {

    			$kill_id = md5($dt . $system_kill->system_id);

    			$system = SystemKills::updateOrCreate([
    				'kill_id'            => $kill_id
    			],[
    				'system_id'          => $system_kill->system_id,
    				'ship_kills'         => $system_kill->ship_kills,
    				'npc_kills'          => $system_kill->npc_kills,
    				'pod_kills'          => $system_kill->pod_kills,
    			]);

    		}
    	}
    }
}
