<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Vanguard\Alliances;
use Vanguard\KnownStructures;
use Vanguard\SolarSystems;
use Vanguard\Stagers;
use Vanguard\AllianceStandings;

use Auth;
use Input;

class StagerController extends Controller
{
    public function index() {

        $search = Input::input('search');

        $standings =        [
            '' => 'All',
            'Friendly' => 'Friendly',
            'Neutral' => 'Neutral',
            'Hostile' => 'Hostile',
        ];


        $alliance = ['' => 'All'];

        $alliances = Stagers::orderBy('alliance_name')->groupBy('alliance_name')->get();

        foreach($alliances as $each_alliance) { 
            if($each_alliance['alliance_name'] == "") {
                // DO Nothing & Don't fucking overright my blank array!
            } else {
                $alliance[$each_alliance['alliance_name']] = $each_alliance['alliance_name']; 
            }
        }

        $region = ['' => 'All'];

        $regions = Stagers::orderBy('region_name')->groupBy('region_name')->get();

        foreach($regions as $each_region) { 
            if($each_region['region_name'] == "") {
                // DO Nothing & Don't fucking overright my blank array!
            } else {
                $region[$each_region['region_name']] = $each_region['region_name']; 
            }
        }

        $tag = ['' => 'All'];

        $tags = Stagers::orderBy('tag')->groupBy('tag')->get();

        foreach($tags as $each_tag) { 
            if($each_tag['tag'] == "") {
                // DO Nothing & Don't fucking overright my blank array!
            } else {
                $tag[$each_tag['tag']] = $each_tag['tag']; 
            }
        }

        $input_tag = Input::input('tag');
        $input_standings = Input::input('standings');
        $input_region = Input::input('region');
        $input_alliance = Input::input('alliance');


        $query = Stagers::query();


        if ($input_alliance) {
            $query->where('alliance_name', $input_alliance);
        }

        if ($input_region) {
            $query->where('region_name', $input_region);
        }

        if ($input_tag) {
            $query->where('tag', $input_tag);
        }

        if ($input_standings == "Friendly") {
            $query->where('standing', '>', 0.00);
        }

        if ($input_standings == "Hostile") {
            $query->where('standing', '<', 0.00);
        }

        if ($input_standings == "Neutral") {
            $query->where('standing', '=', 0.00);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('aliance_name', "like", "%{$search}%");
                $q->orWhere('region_name', 'like', "%{$search}%");
                $q->orWhere('tag', 'like', "%{$search}%");
                $q->orWhere('standing', 'like', "%{$search}%");
                $q->orWhere('region_name', 'like', "%{$search}%");
                $q->orWhere('system_name', 'like', "%{$search}%");
                $q->orWhere('constellation_name', 'like', "%{$search}%");

            });
        }

        $stagers = $query
        ->sortable()
        ->orderBy('alliance_name', 'ASC')
        ->paginate(500);

        if ($search) {
            $stagers->appends(['search' => $search]);
        }

        return view('stagers.index', compact('stagers', 'region', 'alliance', 'tag', 'standings'));
    }


    public function add_system(Request $request) {

        $user = Auth::user();
        $system = $request->input('system');
        $tag = $request->input('tag');

        if($system) { 
            $system_properties = SolarSystems::where('ss_system_name', $system)
            ->first(); 

            $alliance_id = $request->input('alliance_id');
            $alliance_name = $request->input('alliance_name');
            $alliance_ticker = $request->input('alliance_ticker');

            $standings = AllianceStandings::where('as_contact_id', $alliance_id)->first();

            if($standings) {
               $standing = $standings->as_standing;
           } else { 
            $standing = 0; 
        }



        $stager = new Stagers;
        $stager->created_by_user_id            = $user->id;
        $stager->created_by_user_username      = $user->username;
        $stager->solar_system_id               = $system_properties->ss_system_id;
        $stager->solar_system_name             = $system_properties->ss_system_name;
        $stager->constellation_id              = $system_properties->ss_constellation_id;
        $stager->constellation_name            = $system_properties->ss_constellation_name;
        $stager->region_id                     = $system_properties->ss_region_id;
        $stager->region_name                   = $system_properties->ss_region_name;
        $stager->alliance_id                   = $alliance_id;
        $stager->alliance_name                 = $alliance_name;
        $stager->alliance_ticker               = $alliance_ticker;
        $stager->standing                      = $standing;
        $stager->tag                           = $tag;
        $stager->save();

        return redirect()
        ->back()
        ->withSuccess('Added ' . $system . ' to the stagers, please assign alliances to this system.');

    } 

    return view('stagers.index');
}

public function update_standings() {

    $stagers = Stagers::all();

    foreach ($stagers as $stager) {

        # Get Standing

        $standing = AllianceStandings::where('as_contact_id', $stager->alliance_id)->first();

        $stager->standing = $standing->as_standing;
        $stager->save();

    }

    return redirect()
    ->back()
    ->withSuccess('Updated Standings');
}


public function remove($id) {

    $stager = Stagers::where('id', $id)->first();
    $stager->delete();


    return redirect()
    ->back()
    ->withSuccess('System Removed');
}

}

