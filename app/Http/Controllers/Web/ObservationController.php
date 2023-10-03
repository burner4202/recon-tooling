<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Vanguard\Observation;
use Carbon\Carbon;


use Vanguard\SolarSystems;
use Vanguard\Corporations;
use Vanguard\Alliances;

use Auth;

class ObservationController extends Controller
{
	public function index() {

		return view('observation.index');

	}

	public function list() {

		$observations = Observation::sortable()
		->where('state', '<', 2)
		->orderBy('created_at', 'DESC')
		->paginate(100);

		return view('observation.list', compact('observations'));

	}

	public function removed() {

		$observations = Observation::sortable()
		->where('state', 2)
		->orderBy('created_at', 'DESC')
		->paginate(100);

		return view('observation.list', compact('observations'));

	}

	public function create(Request $request) {

		$user = Auth::user();
		$user_id = $user->id;
		$username = $user->username;
		$observations = $request->input('notes');
		$now = Carbon::now()->toDateTimeString();
		$unique_id = md5($now ."_". $username);

		$observation = new Observation;

		$observation->unique_id = $unique_id;
		$observation->observation = $observations;
		$observation->created_by_user_id = $user_id;
		$observation->created_by_username = $username;
		$observation->state = 0;

		/*
		State
		0 = Created
		1 = Reviewed
		2 = Removed
		*/

		$observation->prority = 0;

		/* 
		Integer
		Prority - Decided upon review on the intel.
		0 = To be reviewed.
		1 = Useless
		2 = Low
		3 = High
		4 = Urgent
		*/

		$observation->tags = "";

		/*
		Observation Tag
		string
		Tagged by the reviewer.
		Examples of TAGS, Cyno/FC/Freighters/Dread Cache/Moving to new Stager/TBC
		*/

		$observation->score = 0; 

		/*
		Float
		Rate the Intel on a scale of 1/10
		*/
		$observation->save();

		/* Created At / Updated At timestamps() to be used for creation and review times. */

		$content = 'Observation: ' . substr(strip_tags($observations),0 ,20) . '... by '  . $username . ' : ' . url('/observation/' . $unique_id . '/view');

		$this->postToJabber($content);


		return redirect()->back()->withSuccess('Observation Created.');

	}

	public function view($unique_id) {

		$observation = Observation::where('unique_id', $unique_id)->first();

		if(!$observation) {
			return redirect()->back()->withErrors('Stop being a fuck head.');
		}

		if($observation->state >= 1) {

			return view('observation.view', compact('observation'));

		} else {

			return view('observation.review', compact('observation'));
		}

	}

	public function reviewed(Request $request) {

		$user = Auth::user();
		$user_id = $user->id;
		$username = $user->username;
		$solar_system_name = $request->input('solar_system_name');
		$alliance_name = $request->input('alliance_name');
		$corporation_name = $request->input('corporation_name');
		$prority = $request->input('prority');
		$score = $request->input('score');
		$observation_id = $request->input('observation_id');

		# Validation

		if($solar_system_name) {
			$system = SolarSystems::where('ss_system_name', $solar_system_name)->first();

			if($system) {
				$solar_system_name = $system->ss_system_name;
				$solar_system_id = $system->ss_system_id;
			} else {
				return redirect()->back()->withErrors('Invalid Solar System Name');
			}

		} else {
			$solar_system_name = "";
			$solar_system_id = "";
		}

		if($alliance_name) {
			$alliance = Alliances::where('alliance_name', $alliance_name)->first();

			if($alliance) {
				$alliance_name = $alliance->alliance_name;
				$alliance_id = $alliance->alliance_alliance_id;
				$alliance_ticker = $alliance->alliance_ticker;
			} else {
				return redirect()->back()->withErrors('Invalid Alliance');
			}
		} else {
			$alliance_name = "";
			$alliance_id = "";
			$alliance_ticker  = "";
		}

		if($corporation_name) {
			$corporation = Corporations::where('corporation_name', $corporation_name)->first();

			if($corporation) {
				$corporation_name = $corporation->corporation_name;
				$corporation_id = $corporation->corporation_corporation_id;
				$corporation_ticker = $corporation->corporation_ticker;
			} else {
				return redirect()->back()->withErrors('Invalid Corporation');
			}
		} else {
			$corporation_name = "";
			$corporation_id = "";
			$corporation_ticker = "";
		}

		$observation = Observation::where('unique_id', $observation_id)->first();

		if(!$observation) {
			return redirect()->back()->withErrors('Stop being a fuck head.');
		}

		$observation->state = 1;
		$observation->prority = $prority;
		$observation->solar_system_name = $solar_system_name;
		$observation->solar_system_id = $solar_system_id;
		$observation->alliance_name = $alliance_name;
		$observation->alliance_id = $alliance_id;
		$observation->alliance_ticker = $alliance_ticker;
		$observation->corporation_id = $corporation_id;
		$observation->corporation_name = $corporation_name;
		$observation->corporation_ticker = $corporation_ticker;
		$observation->score = $score;
		$observation->reviewed_by_user_id = $user_id;
		$observation->reviewed_by_username = $username;
		$observation->save();


		return redirect()->route('observation.list')->withSuccess('Marked as Reviewed.');

	}

	public function remove($id) {

		$observation = Observation::where('unique_id', $id)->first();

		$user = Auth::user();
		$user_id = $user->id;
		$username = $user->username;

		$observation->state = 2;
		$observation->reviewed_by_user_id = $user_id;
		$observation->reviewed_by_username = $username;
		$observation->save();

		return redirect()->route('observation.list')->withSuccess('Observation Removed.');

	}


	public function postToJabber($content) {

		$channel = 'new_moons@conference.goonfleet.com';
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

