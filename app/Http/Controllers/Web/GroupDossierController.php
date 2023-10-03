<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Vanguard\Corporations;
use Vanguard\KnownStructures;
use Vanguard\Alliances;
use Vanguard\SystemCostIndices;
use Vanguard\GroupDossier;
use Vanguard\User;
use Carbon\Carbon;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

use Auth;

class GroupDossierController extends Controller
{
	public function index() {

		$dossiers = GroupDossier::orderBY('id', 'DESC')
		->where('state', '>', 0)
		->get();

		return view('dossier.index', compact('dossiers'));
	}

	public function create(Request $request) {

		# Get Input
		$input_corporation = $request->input('corporation');

		if(!$input_corporation) {
			return redirect()
			->back()
			->withErrors('I need a corporation dummy. Stop being a twat.');
		}

		/*
		0 = Draft
		1 = For Review
		2 = Approved
		*/

		$state = 0;

		$owner_id = $this->getCorporationID($input_corporation);
				    // Can't find corporation in database, add it.
		if (!$owner_id) {
					   // Get ID
			$owner_id = $this->searchEVE($input_corporation);
					   // Add to database
			$this->getCorporation($owner_id);
		}

		$corporation = Corporations::where('corporation_corporation_id', $owner_id)->first();
		$alliance = Alliances::where('alliance_alliance_id', $corporation->corporation_alliance_id)->first();

		# Check Structure Database
		$structures = KnownStructures::where('str_owner_corporation_name', $input_corporation)
		->where('str_destroyed', 0)
		->orderBy('str_value', 'DESC')
		->get()
		->take(20);

		# It exists, does it have any structures in the database that are alive?
		if($structures) {

			return view('dossier.create', compact('corporation', 'structures', 'alliance'));
		}

		return view('dossier.create', compact('corporation', 'alliance'));

	}

	public function store(Request $request) {

		$corporation = Corporations::where('corporation_name', $request->input('corporation_name'))->first();
		$alliance = Alliances::where('alliance_name', $request->input('alliance_name'))->first();

		$structures = KnownStructures::where('str_owner_corporation_name', $corporation->corporation_name)
		->where('str_destroyed', 0)
		->orderBy('str_value', 'DESC')
		->get()
		->take(20);

		$is_shell_corporation = $request->input('is_shell_corporation');
		$has_relationship_via_evewho_history = $request->input('has_relationship_via_evewho_history');
		$has_related_killboard_activity = $request->input('has_related_killboard_activity');
		$presence_of_cyno_alts = $request->input('presence_of_cyno_alts');
		$presence_of_freighter_alts = $request->input('presence_of_freighter_alts');
		$locators_confirm_location_of_related_alliance = $request->input('locators_confirm_location_of_related_alliance');
		$has_structures_in_related_system_of_target_alliance = $request->input('has_structures_in_related_system_of_target_alliance');
		$has_structures_in_systems_with_very_high_indexes = $request->input('has_structures_in_systems_with_very_high_indexes');
		$has_structures_on_expensive_money_moons = $request->input('has_structures_on_expensive_money_moons');
		//$intelligence_confirmed = $request->input('intelligence_confirmed');
		$corporation_function = $request->input('corporation_function');
		$has_office_in_alliance_staging = $request->input('has_office_in_alliance_staging');

		$author = $request->input('author');
		$notes = $request->input('notes');

		$user = User::where('username', $author)->first();

		/* Calculation Relationship Score */

		if($is_shell_corporation === "Yes") { $sc_score = 0.10 * 100; } else { $sc_score = 0; }
		if($has_relationship_via_evewho_history === "Yes") { $evewho_score = 0.15 * 100; } else { $evewho_score = 0; }
		if($has_related_killboard_activity === "Yes") { $zkb_score = 0.10 * 100; } else { $zkb_score = 0; }
		if($presence_of_cyno_alts === "Yes") { $cyno_score = 0.05 * 100; } else { $cyno_score = 0; }
		if($presence_of_freighter_alts === "Yes") { $fa_score = 0.05 * 100; } else { $fa_score = 0; }
		if($has_structures_in_related_system_of_target_alliance === "Yes") { $rst_score = 0.20 * 100; } else { $rst_score = 0; }
		if($has_structures_in_systems_with_very_high_indexes === "Yes") { $index_score = 0.10 * 100; } else { $index_score = 0; }
		if($has_structures_on_expensive_money_moons === "Yes") { $moons_score = 0.20 * 100; } else { $moons_score = 0; }
		if($locators_confirm_location_of_related_alliance === "Yes") { $lcl_alliance = 0.05 * 100; } else { $lcl_alliance = 0; }
		if($has_office_in_alliance_staging === "Yes") { $oas = 0.05 * 100; } else { $oas = 0; }

		$relationship_score = ($sc_score + $evewho_score + $zkb_score + $cyno_score + $fa_score + $rst_score + $index_score + $moons_score + $lcl_alliance + $oas);

		$dossier = new GroupDossier;
		$dossier->dossier_title = $request->input('dossier_title');
		$dossier->corporation_id = $corporation->corporation_corporation_id;
		$dossier->corporation_name = $corporation->corporation_name;
		$dossier->alliance_id = $alliance->alliance_alliance_id;
		$dossier->alliance_name = $alliance->alliance_name;
		$dossier->target_alliance_id = $alliance->alliance_alliance_id;
		$dossier->target_alliance_name = $alliance->alliance_name;
		$dossier->structures = json_encode($structures);
		$dossier->is_shell_corporation = $is_shell_corporation;
		$dossier->has_office_in_alliance_staging = $has_office_in_alliance_staging;
		$dossier->has_relationship_via_evewho_history = $has_relationship_via_evewho_history;
		$dossier->has_related_killboard_activity = $has_related_killboard_activity;
		$dossier->presence_of_cyno_alts = $presence_of_cyno_alts;
		$dossier->presence_of_freighter_alts = $presence_of_freighter_alts;
		$dossier->locators_confirm_location_of_related_alliance = $locators_confirm_location_of_related_alliance;
		$dossier->has_structures_in_related_system_of_target_alliance = $has_structures_in_related_system_of_target_alliance;
		$dossier->has_structures_in_systems_with_very_high_indexes = $has_structures_in_systems_with_very_high_indexes;
		$dossier->has_structures_on_expensive_money_moons = $has_structures_on_expensive_money_moons;
		$dossier->intelligence_confirmed = 1;
		$dossier->relationship_score = $relationship_score;
		$dossier->corporation_function = $corporation_function;
		$dossier->created_by_user_id = $user->id;
		$dossier->created_by_username = $user->username;
		$dossier->state = 1;
		$dossier->notes = $notes;
		$dossier->save();

		return redirect()->route('dossier.index')->withSuccess('Dossier Created Successfully.');
		
	}

	public function view($id) {

		$dossier = GroupDossier::where('id', $id)->first();

		$corporation = Corporations::where('corporation_corporation_id', $dossier->corporation_id)->first();

		return view('dossier.view', compact('dossier', 'corporation'));
	}

	public function delete($id) {

		$dossier = GroupDossier::where('id', $id)->first();

		$now = Carbon::now();

		$user = User::where('id', Auth::id())->first();

		$dossier->state = 0;
		$dossier->approved_by_user_id = $user->id;
		$dossier->approved_by_username = $user->username;
		$dossier->approved_date = $now;
		$dossier->save();

		return redirect()->route('dossier.index')->withSuccess('Dossier Successfully Deleted.');
	}

	public function approved($id) {

		$dossier = GroupDossier::where('id', $id)->first();

		$now = Carbon::now();

		$user = User::where('id', Auth::id())->first();

		$dossier->state = 2;
		$dossier->approved_date = $now;
		$dossier->approved_by_user_id = $user->id;
		$dossier->approved_by_username = $user->username;
		$dossier->save();

		
		return redirect()->back()->withSuccess('Approved');
	}

	public function getCorporationID($corporation_name) {

		$corporation = Corporations::where('corporation_name', $corporation_name)
		->first();

		if(!isset($corporation)) {
			return false;
		} else {
			return $corporation->corporation_corporation_id;
		}
	}

	public function getCorporation($corporation_id)
	{
		$configuration = Configuration::getInstance();

		$client_id = config('eve.client_id');
		$secret_key = config('eve.secret_key');

		try {

			$esi = new Eseye();

			$response = $esi->invoke('get', '/corporations/{corporation_id}/', [
				'corporation_id' => $corporation_id,
			]);

			if(!isset($response->alliance_id)) { 
				$corp = Corporations::updateOrCreate([
					'corporation_corporation_id'      => $corporation_id,
				],[
					'corporation_alliance_id'       => "",
					'corporation_ceo_id'            => $response->ceo_id,
					'corporation_creator_id'        => $response->creator_id,
					'corporation_date_founded'      => $response->date_founded,
					'corporation_member_count'      => $response->member_count,
					'corporation_name'              => $response->name,
					'corporation_tax_rate'          => $response->tax_rate,
					'corporation_ticker'            => $response->ticker,
				]);

				//$this->updateCharacter($response->creator_id);
				//$this->updateCharacter($response->ceo_id);


			} else  {

				$corp = Corporations::updateOrCreate([
					'corporation_corporation_id'      => $corporation_id,
				],[
					'corporation_alliance_id'       => $response->alliance_id,
					'corporation_ceo_id'            => $response->ceo_id,
					'corporation_creator_id'        => $response->creator_id,
					'corporation_date_founded'      => $response->date_founded,
					'corporation_member_count'      => $response->member_count,
					'corporation_name'              => $response->name,
					'corporation_tax_rate'          => $response->tax_rate,
					'corporation_ticker'            => $response->ticker,
				]);

				//$this->updateCharacter($response->creator_id);
				//$this->updateCharacter($response->ceo_id);
			}


		}  catch (EsiScopeAccessDeniedException $e) {

			return redirect()->back()
			->withErrors('SSO Token is invalid');


		} catch (RequestFailedException $e) {

			return redirect()->back()
			->withErrors('Got ESI Error');

		} catch (Exception $e) {

			return redirect()->back()
			->withErrors('ESI is fucked');
		}

	}

	public function searchEVE($search)
	{

		try {
			$ammended = str_replace(" ", "%20", $search);
			$response = json_decode(file_get_contents('https://esi.evetech.net/latest/search/?categories=corporation&datasource=tranquility&language=en-us&search=' . $ammended . '&strict=true'));
			return $response->corporation['0'];
		} catch (Exception $e) {

			return redirect()->back()
			->withErrors('ESI Error');

		}
	}

}

