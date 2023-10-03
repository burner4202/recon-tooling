<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Vanguard\Characters;
use Vanguard\Alliances;
use Vanguard\Corporations;
use Vanguard\SolarSystems;

use Vanguard\User;
use Vanguard\ESITokens;
use Vanguard\TypeIDs;

use Vanguard\AugswarmTracking;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

use Carbon\Carbon;

use Auth;

use Vanguard\Jobs\AugSwarms\updateCharacterInformationJob;
use Vanguard\Jobs\Characters\UpdateCharacterJob;
use Queue;

use Vanguard\APICalls;

class AugswarmTrackingController extends Controller
{
    public function index() {

        $query = AugswarmTracking::query();

        $augswarms = $query
        ->sortable()
        ->leftjoin('esi_tokens', function($join)
        {
            $join->on('augswarm_tracking.at_character_id', '=', 'esi_tokens.esi_character_id');
            # Can add multiple queries here on the current data set.
        })
        ->leftjoin('solar_system', function($join)
        {
            $join->on('augswarm_tracking.at_solar_system_id', '=', 'solar_system.ss_system_id');
            # Can add multiple queries here on the current data set.
        })
        ->get();

        return view('augswarm.index', compact('augswarms'));
    }

    public function create(Request $request) {
        # Search for a Character
        # Check if this character exists in the database;

        $character_name = $request->input('search');

        $character = ESITokens::where('esi_name', $character_name)
        ->where('esi_active', 1)
        ->first();

        if(!$character) {
        # This character does not exist in the esi database, add it.
            return redirect()->route('augswarm.index')->withErrors('This character does not have an active ESI Token.');
        }

        $update = AugswarmTracking::updateOrCreate([
            'at_character_id'        => $character->esi_character_id,
        ],[
            'at_solar_system_id'     => 30004759,
        ]);

        ## Push Update Job for this character.

        $this->dispatch(new updateCharacterInformationJob($character->esi_character_id));

        return redirect()->route('augswarm.index')->withSuccess('Character sucessfully added for tracking.');
    }

    public function update() {

        # Push Job to update all Augswarms

        $augswarms = AugswarmTracking::get();

        if(count($augswarms) > 0) {
            foreach($augswarms as $character) {
                # Dispatch Job to update.

                $this->dispatch(new updateCharacterInformationJob($character->at_character_id));

            }

            return redirect()->route('augswarm.index')->withSuccess('Dispatched ' . count($augswarms) . ' jobs to update characters.');
        } else {

            return redirect()->route('augswarm.index')->withErrors('No augswarms are currently being tracked.');
        }
    }

    public function remove($character_id) {

        $augswarm = AugswarmTracking::where('at_character_id', $character_id)->first();

        if ($augswarm) {

            $augswarm->delete();

            return redirect()->route('augswarm.index')->withSuccess($augswarm->at_character_name . ' removed from tracking database.');
        }

        return redirect()->route('augswarm.index')->withErrors('This augswarm does not exist.');
    }

    public function formatEveDate($date) {
        $trimmed = rtrim($date, "Z");
        $dateAndTime = explode("T", $trimmed);
        $dt = Carbon::parse($dateAndTime[0] . " " . $dateAndTime[1]);   
        return $dt;   
    }

    public function augswarms(Request $request) {

        $token = null;
        $user_agent = null;
        $ip_address = null;
        $endpoint = "/administration/augswarms";
        $gsf_user = "";

        $headers = $request->headers;

        //dd($headers);

        # Get token From Header.
        foreach($headers as $index => $header) {
            ## Get token Header
            if($index == "token") {
                $token = $header[0];
            }

            if($index == "x-real-ip" || $index == "x-client-ip") {
                $ip_address = $header[0];
            }

            if($index == "user-agent" ) {
                $user_agent = $header[0];
            }

            if($index == "x-gsf-user") {
                $gsf_user = $header[0];
            }


            ## Get API Key Header.
            ## Do API Stuff
        }

        $users = User::all()->pluck('email')->toArray();

        # Check we have a token header.
        if($token == "3aa2bd23a26bf2a95ef4a14df9b3fb7a") {

            ## Check User Permissions Here

            if(!in_array($gsf_user, $users)) {
                # Add to API Log
                $this->recordAPIRequest($ip_address, $user_agent, $endpoint, $gsf_user, '401');
                # Go away.
                return response()->json('Unauthorized, Invalid Username', 401);
            } 

            $query = AugswarmTracking::query();

            $augswarms = $query
            ->leftjoin('esi_tokens', function($join)
            {
                $join->on('augswarm_tracking.at_character_id', '=', 'esi_tokens.esi_character_id');
            # Can add multiple queries here on the current data set.
            })
            ->leftjoin('solar_system', function($join)
            {
                $join->on('augswarm_tracking.at_solar_system_id', '=', 'solar_system.ss_system_id');
            # Can add multiple queries here on the current data set.
            })
            ->orderBy('esi_name', 'ASC')
            ->where('at_online', 1)        
            ->get();

            if($augswarms) {
                # Add to API Log
                $this->recordAPIRequest($ip_address, $user_agent, $endpoint, $gsf_user, '200');
                # Found the Structure Name, Respond.

                $online = array();

                foreach($augswarms as $character) {

                    $online[] = $character->esi_name . ' (' . $character->ss_system_name . ' - ' . $character->ss_region_name . ' - ' . $character->at_ship_type_id_name . ')';

                }

                $string = implode(" | ",$online);
                $data = [
                    'online' => $string
                ];

                return response()->json($data, 200);
            }

        } else {
            # Add to API Log
            $this->recordAPIRequest($ip_address, $user_agent, $endpoint, $gsf_user, '401');
            # Go away.
            return response()->json('Unauthorized, Invalid Token', 401);

        }

    }

    public function recordAPIRequest($ip, $agent, $endpoint, $gsf_user, $response) {

        $log = new APICalls;
        $log->apc_ip_address = $ip;
        $log->apc_user_agent = $agent;
        $log->apc_endpoint = $endpoint;
        $log->apc_gsf_username = $gsf_user;
        $log->apc_response = $response;
        $log->save();
    }
}



