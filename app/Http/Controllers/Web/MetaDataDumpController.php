<?php

/*
 * Goonswarm Federation Recon Tools
 *
 * Developed by scopehone <scopeh@gmail.com>
 * In conjuction with Natalya Spaghet & Mindstar Technology 
 *
 */


namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Datetime;

use Auth;
use Log;
use DB;
use Input;
use Carbon\Carbon;
use Vanguard\User;
use Vanguard\SolarSystems;
use Vanguard\Regions;
use Vanguard\Constellations;
use Vanguard\TypeIDs;
use Vanguard\NPCKills;
use Vanguard\KnownStructures;
use Vanguard\Corporations;
use Vanguard\Alliances;
use Vanguard\UpwellModules;
use Vanguard\ActivityTracker;
use Vanguard\MarketPrices;
use Vanguard\UpwellRigs;
use Vanguard\AllianceStandings;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class MetaDataDumpController extends Controller
{
	public function index() {

		return view('metadata.index');

	}

	public function metaDataDump() {

		$structures = array();
		$store = array();

		$request = Input::all();

		if($request['title'] == null) {
			return redirect()->back()
			->withErrors('Fill the box you dick.'); 
		}

		$lines = explode("\r\n", $request['title']);

		foreach ($lines as $line) {

			if(strpos($line, '<url=showinfo:') == false) {
				return redirect()->back()
				->withErrors('Invalid Dscan Parse');
			}


		// Check to make sure names match.

			$exploded = explode("<url=showinfo:", $line);
			$type_id_structure_id = explode("//", $exploded[1]);
			$type_id = $type_id_structure_id[0];
			$parsed_structure_id = substr($type_id_structure_id[1], 0, 13);

			$structure_name = rtrim(substr($type_id_structure_id[1], 14), "</url>");

			$systems = SolarSystems::all()->pluck('ss_system_name')->toArray();

		# Check if the Solar System Name is in the string.

			$system_check = explode(" - ", $structure_name);

		//$system_filtered = str_replace($search_planet, "", $system_check[0]);

			foreach($systems as $system) {

				$pattern = "/\b" . $system . "\b/";

				if(preg_match($pattern, $system_check[0]))
				{
					$checked_system = $system;

				}

			}

			$explode_name =  explode(" (", $system_check[1]);
			$actual_name = $explode_name[0];

			$saved_structure_name = explode(" (", $structure_name);

			// Corporations are in Brackets, this checks to se eif brackets are present for validation
			$corporation =  preg_match_all('/\(([^\)]+)\)/', $structure_name, $matches);

			$owner = $matches[1];	

			if(strlen($parsed_structure_id)  != 13) {
				return redirect()->back()
				->withErrors('Invalid Dscan Parse');
			} else { 

				if($this->validStructure($type_id)) {

					// Make the Hash
					// System ID . Type ID. Structure Name . Struture Type
					$system_id = SolarSystems::where('ss_system_name', $checked_system)->first();
					$structure_type = KnownStructures::where('str_type_id', $type_id)->first();
					$structure_size = $this->structureSize($type_id);

					$md5 = md5($system_id->ss_system_id . $type_id . $saved_structure_name[0] . $structure_type->str_type);	

					$owner_id = $this->getCorporationID(end($owner));
				    // Can't find corporation in database, add it.
					if (!$owner_id) {
					   // Get ID
						$owner_id = $this->searchEVE(end($owner));
					   // Add to database
						$this->getCorporation($owner_id);
					}

					$corporation = Corporations::where('corporation_corporation_id', $owner_id)->first();
					$alliance = Alliances::where('alliance_alliance_id', $corporation->corporation_alliance_id)->first();




					$addStructure = KnownStructures::updateOrCreate([
						'str_structure_id_md5' 				 => $md5,
					],[
						'str_structure_id'     				 => $parsed_structure_id,
						'str_system_id'						 => $system_id->ss_system_id,
						'str_system'						 => $system_id->ss_system_name,
						'str_constellation_id'				 => $system_id->ss_constellation_id,
						'str_constellation_name'			 => $system_id->ss_constellation_name,
						'str_region_id'						 => $system_id->ss_region_id,
						'str_region_name'					 => $system_id->ss_region_name,
						'str_type_id'						 => $type_id,
						'str_name'							 => $saved_structure_name[0],
						'str_type'							 => $structure_type->str_type,
						'str_size'							 => $structure_size,
						'str_owner_corporation_name'     	 => end($owner),
						'str_owner_corporation_id'     	 	 => $owner_id,
					]);

					if($corporation->corporation_alliance_id > 1) {


						# Search Standings , Corporation or Alliance 
						$standings = AllianceStandings::where('as_contact_id', $alliance->alliance_alliance_id)->first();

						# Found Standings - Add to Structure
						if($standings) {

							$add_standings = KnownStructures::where('str_structure_id_md5', $md5)->first();
							$add_standings->str_standings = $standings->as_standing;
							$add_standings->save();
							
						} else {

						# Standings were not found, this is a neutral structure
							$add_standings = KnownStructures::where('str_structure_id_md5', $md5)->first();
							$add_standings->str_standings = 0;
							$add_standings->save();
						}

						$update = KnownStructures::where('str_structure_id_md5', $md5)->first();
						$update->str_owner_alliance_id = $alliance->alliance_alliance_id;
						$update->str_owner_alliance_name = $alliance->alliance_name;
						$update->str_owner_alliance_ticker = $alliance->alliance_ticker;
						$update->save();

					} else {

						$update = KnownStructures::where('str_structure_id_md5', $md5)->first();
						$update->str_owner_alliance_id = 0;
						$update->str_owner_alliance_name = null;
						$update->str_owner_alliance_ticker = null;
						$update->save();
					}

				   // Add Action to Activity Log
					$user_id = Auth::id();
					$action = "Structure Belongs to " . end($owner);
					$this->addActivityLogToStructureMeta($user_id, $md5, $parsed_structure_id, $owner_id, end($owner), $action);


				}

			}
		}

		return redirect()->back()
		->withSuccess('Piece of Cake...');


	}

	public function validStructure($structure_id) {
		$structures = [
    		//Fortizars
			'35832',
			'35833',
			'35834',
    		// Faction Fortizars
			'47512',
			'47513',
			'47514',
			'47515',
			'47516',
			'40340',
   			// Engineering
			'35825',
			'35826',
			'35827',
			'35840',
			// StructuresCyno Etc
			'35841',
			'35841',
			'37534',
			// Moon
			'35835',
			'35836',
		];

		if (in_array($structure_id, $structures)) {
			return true;
		} else {
			return false;
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
			->withErrors('Invalid Fitting');

		}
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

	public function addActivityLogToStructureMeta($user_id, $structure_id_md5, $structure_id, $corporation_id, $corporation_name, $user_action) {

		$user = User::where('id', $user_id)
		->first();

		$action = new ActivityTracker;
		$action->at_user_id = $user->id;
		$action->at_username = $user->username;
		$action->at_structure_id = $structure_id;
		$action->at_structure_hash = $structure_id_md5;
		$action->at_corporation_id = $corporation_id;
		$action->at_corporation_name = $corporation_name;
		$action->at_action = $user_action;
		$action->save();
	}

	public function structureSize($structure_type) {


		$structures = [

    		//Fortizars
			'35832' => 'Medium',
			'35833' => 'Large',
			'35834' => 'Extra Large',

    		// Faction Fortizars
			'47512' => 'Large',
			'47513' => 'Large',
			'47514' => 'Large',
			'47515' => 'Large',
			'47516' => 'Large',
			'40340' => 'Extra Large',

    		// Engineering
			'35825' => 'Medium',
			'35826' => 'Large',
			'35827' => 'Extra Large',


    		// StructuresCyno Etc
			'35840' => 'FLEX',
			'35841' => 'FLEX',
			'37534' => 'FLEX',

    		// Moon

			'35835' => 'Medium',
			'35836' => 'Large',

		];

		if(isset($structures[$structure_type]))
		{
			return $structures[$structure_type];
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



}
