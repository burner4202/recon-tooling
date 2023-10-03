<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Vanguard\Characters;
use Vanguard\Alliances;
use Vanguard\Corporations;
use Vanguard\SolarSystems;
use Vanguard\CharacterReport;
use Vanguard\CharacterRelationship;
use Vanguard\AllianceEnemyStandings;
use Vanguard\GroupDossier;
use Vanguard\PublicContracts;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

use Carbon\Carbon;

use Auth;

class CharactersController extends Controller
{
	public function index(Request $request) {

		$character_name = $request->input('search');

		if($character_name) {
			return redirect()->route('character.create', $character_name);
		}

		return view('characters.index');
	}

	public function create($character_name) {

		# Search for a Character
		# Check if this character exists in the database;

		$character = Characters::where('character_name', $character_name)->first();

		if(!$character) {
		# This character does not exist in the database, add it.

			$character_id = $this->addCharacterToDatabase($character_name);

			if(!$character_id) {
				return redirect()->route('character.index')->withErrors('This character does not exist in EVE online. Dummy.');
			}

			$character = Characters::where('character_character_id', $character_id)->first();

		}

		return redirect()->route('character.view', $character_name);
		
	}

	public function view($character_name) {

		$character = Characters::where('character_name', $character_name)
		->first();

		$reports = CharacterReport::where('character_name', $character_name)
		->orderBy('created_at', 'DESC')
		->get();

		$relationships = CharacterRelationship::where('character_name', $character_name)
		->orWhere('associated_character_name', $character_name)
		->orderBy('associated_character_name', 'ASC')
		->get();

		$standings = AllianceEnemyStandings::where('as_contact_id', $character->character_character_id)
		->groupBy('as_enemy_alliance_name')
		->get();

		$dossiers = GroupDossier::where('corporation_name', $character->character_corporation_name)
		->where('state', 2)
		->groupBy('corporation_name')
		->get();

		$contracts = PublicContracts::where('character_name', $character_name)
		->orderBy('date_issued', 'DESC')
		->get();

		$related_characters = CharacterReport::where('corporation_name', $character->character_corporation_name)
		->where('character_name', '!=', $character_name)
		->orderBy('character_name', 'ASC')
		->get();


		return view('characters.view', compact('character', 'reports', 'standings', 'dossiers', 'relationships', 'contracts', 'related_characters'));
	}

	public function store_report(Request $request) {

		$alliance_name = $request->input('alliance_name');
		$system_name = $request->input('system_name');
		$spotted_hull = $request->input('spotted_hull');
		$character_name = $request->input('character_name');
		$notes = $request->input('notes');

		if(!$system_name || !$spotted_hull) {
			return redirect()->back()->withErrors('System & Hull fields are required for a report entry.');
		}

		$alliance = Alliances::where('alliance_name', $alliance_name)->first();
		$system = SolarSystems::where('ss_system_name', $system_name)->first();

		# Have we got an alliance to check;
		if($alliance_name) {
			$alliance = Alliances::where('alliance_name', $alliance_name)->first();
			# Does it exist?
			if(!$alliance) {
				$alliance_id = "";
				$alliance_name = "";
			} else {
				$alliance_id = $alliance->alliance_alliance_id;
				$alliance_name = $alliance->alliance_name;
			} 
		} else {
			$alliance_id = "";
			$alliance_name = "";
		}



		if(!$system) {
			return redirect()->back()->withErrors('This system does not exist');
		}

		$character = Characters::where('character_name', $character_name)->first();

		if($spotted_hull == "Jump Freighter") {
			$character->jump_freighter = 1;
		}

		if($spotted_hull == "Super") {
			$character->super = 1;
		}

		if($spotted_hull == "Titan") {
			$character->titan = 1;
		}

		if($spotted_hull == "Monitor") {
			$character->monitor = 1;
		}

		if($spotted_hull == "Freighter") {
			$character->freighter = 1;
		}

		if($spotted_hull == "Cyno") {
			$character->cyno = 1;
		}

		if($spotted_hull == "Industrial Cyno") {
			$character->industrial_cyno = 1;
		}

		if($spotted_hull == "Rorqual") {
			$character->rorqual = 1;
		}

		if($spotted_hull == "Carrier") {
			$character->carrier = 1;
		}

		$character->save();

		## Add Report
		$report = new CharacterReport;
		$report->character_id = $character->character_character_id;
		$report->character_name = $character->character_name;
		$report->corporation_id = $character->character_corporation_id;
		$report->corporation_name = $character->character_corporation_name;
		$report->system_id = $system->ss_system_id;
		$report->system_name = $system->ss_system_name;
		$report->constellation_id = $system->ss_constellation_id;
		$report->constellation_name = $system->ss_constellation_name;
		$report->region_id = $system->ss_region_id;
		$report->region_name = $system->ss_region_name;
		$report->alliance_id = $alliance_id;
		$report->alliance_name = $alliance_name;
		$report->hull_type = $spotted_hull;
		$report->notes = $notes;
		$report->save();


		return redirect()->back()->withSuccess('Awesome, another one dead.');
	}

	public function store_relationship(Request $request) {

		$user = Auth::user();

		$character_name = $request->input('character_name');
		$associated_character = $request->input('associated_character');
		
		$notes = $request->input('notes');

		if(!$associated_character) {
			return redirect()->back()->withErrors('I need a name dummy.');
		}

		$character = Characters::where('character_name', $associated_character)->first();
		$owner = Characters::where('character_name', $character_name)->first();

		if(!$character) {
			return redirect()->back()->withErrors('This character does not exist, add it first, before adding a relationship.');
		}

		## Add Report
		$relationship = new CharacterRelationship;
		$relationship->character_id = $owner->character_character_id;
		$relationship->character_name = $owner->character_name;
		$relationship->associated_character_id = $character->character_character_id;
		$relationship->associated_character_name = $character->character_name;
		$relationship->associated_corporation_id = $character->character_corporation_id;
		$relationship->associated_corporation_name = $character->character_corporation_name;
		$relationship->associated_alliance_id = $character->character_alliance_id;
		$relationship->associated_alliance_name = $character->character_alliance_name;
		$relationship->notes = $notes;
		$relationship->added_by = $user->username;
		$relationship->save();

		return redirect()->back()->withSuccess('Relationship Added.');

	}



	public function addCharacterToDatabase($character_name) {


		# Recieved a character name, search CCP for it, add it to the database.
		$character = $this->searchEVE($character_name);

		if(!$character) {
			return false;
		} else {

			$this->addCharacter($character);

			return $character;

		}		

	}


	public function searchEVE($search)
	{

		try {
			$ammended = str_replace(" ", "%20", $search);
			$response = json_decode(file_get_contents('https://esi.evetech.net/latest/search/?categories=character&datasource=tranquility&language=en-us&search=' . $ammended . '&strict=true'));

			if(!isset($response->character[0])) {
				return false;
			} else {
				return $response->character[0];
			}
		} catch (Exception $e) {

			return redirect()->back()
			->withErrors('ESI Error');

		}
	}


	public function addCharacter($character_id) {

		$response = $this->getCharacter($character_id);

        # Check if we have the corporation cached.

		$corporation_cache = Corporations::where('corporation_corporation_id', $response->corporation_id)->first();

		if($corporation_cache) {

			$alliance_id = $corporation_cache->corporation_alliance_id;
			$corporation_name = $corporation_cache->corporation_name;

		} else {

             ## Ask CCP for the Info.
			$corporation = $this->getCorporation($response->corporation_id);
			$corporation_name = $corporation->name;

                            # If corporation is part of an alliance, set id.

			if(isset($corporation->alliance_id)) {
				$alliance_id = $corporation->alliance_id;
			} else {
				$alliance_id = "";
			}


		}


            # If the alliance id is more than 0, it exists.

		if($alliance_id > 0) {

                # Check if we have alliance cached.

			$alliance_cache = Alliances::where('alliance_alliance_id', $alliance_id)->first();

			if($alliance_cache) {

                    # It exists.

				$alliance_name = $alliance_cache->alliance_name;

			} else {

                    # Get Endpoint from CCP.

				$alliance = $this->getAlliance($alliance_id);

				$alliance_name = $alliance->name;

			}

                # Alliance not found. zero it out.

		} else {

			$alliance_name = "";
			$alliance_id = "";
		}

            # Update the Character Database

		$character = Characters::updateOrCreate([
			'character_character_id'        => $character_id,
		],[
			'character_corporation_id'      => $response->corporation_id,
			'character_corporation_name'    => $corporation_name,
			'character_name'                => $response->name,
			'character_security_status'     => $response->security_status,
			'character_alliance_id'         => $alliance_id,
			'character_alliance_name'       => $alliance_name,
		]);

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
					'corporation_ceo_id'            => $response->ceo_id,
					'corporation_creator_id'        => $response->creator_id,
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

			$this->error('SSO Token is invalid');

		} catch (RequestFailedException $e) {

			$this->error('Got an ESI Error');

		} catch (Exception $e) {

			$this->error('ESI is fucked');
		}


		return $response;

	}



	public function getAlliance($alliance_id)
	{
		$configuration = Configuration::getInstance();

		$client_id = config('eve.client_id');
		$secret_key = config('eve.secret_key');

		try {

			$esi = new Eseye();

			$response = $esi->invoke('get', '/alliances/{alliance_id}/', [
				'alliance_id' => $alliance_id,
			]);


			$alliance = Alliances::updateOrCreate([
				'alliance_alliance_id'                      => $alliance_id,
			],[
				'alliance_creator_corporation_id'            => $response->creator_corporation_id,
				'alliance_creator_id'                           => $response->creator_id,
				'alliance_date_founded'                         => $response->date_founded,
				'alliance_executor_corporation_id'              => $response->executor_corporation_id,
				'alliance_name'                             => $response->name,
				'alliance_ticker'                           => $response->ticker,
			]);


            //$this->updateCharacter($response->creator_id);
            //$this->updateCorporationsOfAlliance($alliance_id);
            //$this->updateCorporation($response->creator_corporation_id);
            //$this->updateCorporation($response->executor_corporation_id);



		}  catch (EsiScopeAccessDeniedException $e) {

			$this->error('SSO Token is invalid');

		} catch (RequestFailedException $e) {

			$this->error('Got an ESI Error');

		} catch (Exception $e) {

			$this->error('ESI is fucked');
		}

		return $response;
	}

	public function getCharacter($character_id)
	{

		$configuration = Configuration::getInstance();

		$client_id = config('eve.client_id');
		$secret_key = config('eve.secret_key');

		try {

			$esi = new Eseye();

			$response = $esi->invoke('get', '/characters/{character_id}/', [
				'character_id' => $character_id,
			]);

		}  catch (EsiScopeAccessDeniedException $e) {

			$this->error('SSO Token is invalid');

		} catch (RequestFailedException $e) {

			$this->error('Got an ESI Error ');
			$this->info($character_id);

		} catch (Exception $e) {

			$this->error('ESI is fucked');
		}

		return $response;

	}
}
