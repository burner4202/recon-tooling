<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Auth;
use Input;
use DB;
use Carbon\Carbon;
use Vanguard\User;
use Vanguard\Alliances;
use Vanguard\Corporations;
use Vanguard\ACLAudit;
use Vanguard\ACLCharacters;
use Vanguard\Standings;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class ACLCheckerController extends Controller
{
	public function index() {

		$acls = ACLAudit::all();
		$acl_members = ACLCharacters::all();
		
		return view('acl_audit.index', compact('acls', 'acl_members'));
	}

	public function view($id) {

		$acl = ACLAudit::where('acl_hash', $id)->first();
		$acl_members = ACLCharacters::where('aclc_acl_hash', $id)->orderBy('aclc_character_name')->get();
		
		return view('acl_audit.view', compact('acl', 'acl_members'));
	}

	public function addName(Request $request, $id) {

		$acl_name = $request->input('acl_name');

		if($acl_name == "") {

			return redirect()->back()->withErrors('Name cannot be empty.');
		}

		$acl = ACLAudit::where('acl_hash', $id)->first();
		
		$acl->acl_name = $acl_name;
		$acl->save();

		return redirect()->back()->withSuccess('Name Added to ACL.');
	}

	public function auditACLCharacters(Request $request) {

		$user = Auth::user();
		$acl_member_type = "";

		$acl_characters = $request->input('acl_characters');

		if($acl_characters == null) {
			return redirect()->back()
			->withErrors('No Data, Try putting something in the box, duh!'); 
		}

		// https://pst.klgrth.io/paste/vyt9om6zjxkntanuystdfunxamkyhzsa - logz
		// 2019.11.17 02:03:11	Delta Flux added Miss NaggyPants as admin	
		// 2019.11.17 02:03:11\tDelta Flux added Miss NaggyPants as admin\t\r\n

		# Explode into Array
		$characters = explode("\t\r\n", $acl_characters);

		$reverse_characters = array_reverse($characters);

		# Go through each line and do something..

		foreach ($reverse_characters as $key => $line) {

			# Count the records for an existing ACL if it exists, skip the previous records already parsed by look at the count and index key on the array.
			//dd($key);

			# Explode out datetime and description
			# 2019.11.17 02:03:11\tDelta Flux added Miss NaggyPants as admin

			list($datetime, $description) = explode("\t", $line);
			$fix_datetime = str_replace(".", "-", $datetime);
			$carbon_date = Carbon::parse($fix_datetime);

			# "2019.11.17 02:03:11"
			# "Delta Flux added Miss NaggyPants as admin"
			
			# Run some regex to see whats going on.

			# There are a number of actions on the ACL
			# added
			# remove # This is if they are a manager.
			# removed
			# changed
			# created # We will use this for the hash.

			# Lets go through each one.

			# Created first, as we want to try to do this sequentially.
			if(preg_match('/\b created \b/', $description)) {

				# We got the genesis line!, lets check to see if we have this in the database.
				# Make hash - Primary.
				$hash = md5($datetime . " - " . $description);

				# Artcanin created access list

				list($character, $action) = explode(" created access list", $description);

				# Ok, got character name, lets see if we have it in the database 
				$character_info = ACLCharacters::where('aclc_character_name', $character)
				->where('aclc_character_id', '>', 3)
				->first();

				if($character_info) { 
					$character_id = $character_info->aclc_character_id;
				} else {

					# Make an ESI Call.
					$search_character_by_name = $this->searchEVEcharacter($character);
					$character_id = $search_character_by_name;
					//$esi_character = $this->getCharacter($character_id);
				}

				ACLAudit::updateOrCreate([
					'acl_hash'     					=> $hash,
				],[
					'acl_added_by'            		=> $user->username,
					'acl_created_time'      		=> $carbon_date,
					'acl_raw'      					=> $acl_characters,
					'acl_public'      				=> 0,
				]);

					## Define ACLC Hash

				$aclc_hash = md5($hash . " - " . $character_id);

				ACLCharacters::updateOrCreate([
					'aclc_hash'     				=> $aclc_hash,
				],[
					'aclc_acl_hash' 				=> $hash,
					'aclc_character_name'           => $character,
					'aclc_character_id'      		=> $character_id,
					'aclc_state'      				=> 'created',
					'aclc_action_date'				=> $carbon_date,
					'aclc_member_type'				=> 'character',
				]);

				
			} 

			## Key Check should be done here. 
			## After the created/first line of the ACL has been parsed to check the hash.


			if(preg_match('/\badded \'Everyone\b/', $description)) {

				ACLAudit::updateOrCreate([
					'acl_hash'     					=> $hash,
				],[
					'acl_public'      				=> 1,
				]);

			}

			if(preg_match('/\bremoved \'Everyone\b/', $description)) {

				ACLAudit::updateOrCreate([
					'acl_hash'     					=> $hash,
				],[
					'acl_public'      				=> 0,
				]);

			}


			if(preg_match('/\b added \b/', $description)) {
			# Created first, as we want to try to do this sequentially.


				list($character, $action) = explode(" added ", $description);
				list($added_character, $role) = explode(" as ", $action);

				# Ok, got character name, lets see if we have it in the database
				$character_info = ACLCharacters::where('aclc_character_name', $added_character)
				->where('aclc_character_id', '>', 3)
				->first();

				if($character_info) { 
					$character_id = $character_info->aclc_character_id;
					$acl_member_type = $character_info->aclc_member_type;
				} else {

					# Make an ESI Call.
					$search_character_by_name = $this->searchEVEcharacter($added_character);
					$character_id = $search_character_by_name;
					$acl_member_type = 'character';

					# Character Search returned 0, so we search incase its a corporation.
					if($character_id == 0) {
						# Character Search Failed, Search Corporation

						$search_corporation = $this->searchEVEcorporation($added_character);
						$character_id = $search_corporation;
						$acl_member_type = 'corporation';
					} 

					if($character_id == 1) {
						$search_alliance = $this->searchEVEAlliance($added_character);
						$character_id = $search_alliance;
						$acl_member_type = 'alliance';

					}

				}

					## Define ACLC Hash

				$aclc_hash = md5($hash . " - " . $character_id);

				ACLCharacters::updateOrCreate([
					'aclc_hash'     				=> $aclc_hash,
				],[
					'aclc_acl_hash' 				=> $hash,
					'aclc_character_name'           => $added_character,
					'aclc_character_id'      		=> $character_id,
					'aclc_state'      				=> 'added',
					'aclc_role'      				=> $role,
					'aclc_action_date'				=> $carbon_date,
					'aclc_member_type'				=> $acl_member_type,
				]);
			}

				## END

			if(preg_match('/\b changed \b/', $description)) {

				list($character, $action) = explode(" changed ", $description);

				if(!preg_match('/\b changed access\b/', $description)) {

					list($changed_character, $role) = explode(" to ", $action);

					$character_info = ACLCharacters::where('aclc_character_name', $changed_character)->first();

					$state = 'changed';

						# Ok, we got the state change, lets filter it.
						# Changed to Admin from Member
					if(preg_match('/\badmin from member\b/', $role)) {
						$import_role = 'admin';
					}

					if(preg_match('/\badmin from manager\b/', $role)) {
						$import_role = 'admin';
					}

						# Changed to Member from Admin
					if(preg_match('/\bmember from admin\b/', $role)) {
						$import_role = 'member';
					}

						# Changed to Manager from Member
					if(preg_match('/\bmanager from member\b/', $role)) {
						$import_role = 'manager';
					}

					# Changed to Manager from Member
					if(preg_match('/\bmanager from admin\b/', $role)) {
						$import_role = 'manager';
					}

						# Changed to Member from Manager
					if(preg_match('/\bmember from manager\b/', $role)) {
						$import_role = 'member';
					}

						# Changed to Blocked Member from Member
					if(preg_match('/\bblocked member from member\b/', $role)) {
						$import_role = 'blocked';
					}

						# Changed to Blocked Member from Manager
					if(preg_match('/\bblocked member from manager\b/', $role)) {
						$import_role = 'blocked';
					}

						# Changed to Blocked Member from Admin
					if(preg_match('/\bblocked member from admin\b/', $role)) {
						$import_role = 'blocked';
					}

						# Changed to Member from Blocked Member
					if(preg_match('/\bmember from blocked member\b/', $role)) {
						$import_role = 'member';
					}

						## Define ACLC Hash
					$aclc_hash = md5($hash . " - " . $character_info->aclc_character_id);

					ACLCharacters::updateOrCreate([
						'aclc_hash'     				=> $aclc_hash,
					],[
						'aclc_acl_hash' 				=> $hash,
						'aclc_character_name'           => $changed_character,
						'aclc_character_id'      		=> $character_info->aclc_character_id,
							//'aclc_state'      				=> $state,
						'aclc_role'      				=> $import_role,
						'aclc_action_date'				=> $carbon_date,
						'aclc_member_type'				=> $character_info->aclc_member_type,
					]);

				} 
			}

			if(preg_match('/\b removed \b/', $description)) {

				list($character, $action) = explode(" removed ", $description);
				list($removed_character, $role) = explode(" (", $action);


				$character_info = ACLCharacters::where('aclc_character_name', $removed_character)
				->first();

					## Define ACLC Hash
				$aclc_hash = md5($hash . " - " . $character_info->aclc_character_id);

				ACLCharacters::updateOrCreate([
					'aclc_hash'     				=> $aclc_hash,
				],[
					'aclc_acl_hash' 				=> $hash,
					'aclc_character_name'           => $removed_character,
					'aclc_character_id'      		=> $character_info->aclc_character_id,
					'aclc_state'      				=> 'removed',
					'aclc_action_date'				=> $carbon_date,
					'aclc_member_type'				=> $character_info->aclc_member_type,
				]);

				


			} 

			if(preg_match('/\b remove \b/', $description)) {

				list($character, $action) = explode(" remove ", $description);
				list($removed_character, $role) = explode(" (", $action);

				$character_info = ACLCharacters::where('aclc_character_name', $removed_character)->first();


				if($character_info) {
				## Define ACLC Hash
					$aclc_hash = md5($hash . " - " . $character_info->aclc_character_id);

					ACLCharacters::updateOrCreate([
						'aclc_hash'     				=> $aclc_hash,
					],[
						'aclc_acl_hash' 				=> $hash,
						'aclc_character_name'           => $removed_character,
						'aclc_character_id'      		=> $character_info->aclc_character_id,
						'aclc_state'      				=> 'removed',
						'aclc_action_date'				=> $carbon_date,
						'aclc_member_type'				=> $character_info->aclc_member_type,
					]);

				}


			}






		}

		return redirect()->back()->withSuccess('ACL Parsed');


	}


	public function searchEVEcharacter($search)
	{
		try {
			$ammended = str_replace(" ", "%20", $search);
			$response = json_decode(file_get_contents('https://esi.evetech.net/latest/search/?categories=character&datasource=tranquility&language=en-us&search=' . $ammended . '&strict=true'), TRUE);

			if(isset($response['character'][0])) {
				$character_id = $response['character'][0];
			} else {

				$character_id = 0;
			}



			return $character_id;
		} catch (Exception $e) {

			return redirect()->back()
			->withErrors('Character Not Found');

		}
	}

	public function searchEVEcorporation($search)
	{
		try {
			$ammended = str_replace(" ", "%20", $search);
			$response = json_decode(file_get_contents('https://esi.evetech.net/latest/search/?categories=corporation&datasource=tranquility&language=en-us&search=' . $ammended . '&strict=true'), TRUE);

			if(isset($response['corporation'][0])) {
				$corporation_id = $response['corporation'][0];
			} else {

				$corporation_id = 1;
			}

			return $corporation_id;
		} catch (Exception $e) {

			return redirect()->back()
			->withErrors('Corporation Not Found');

		}
	}

	public function searchEVEAlliance($search)
	{
		try {
			$ammended = str_replace(" ", "%20", $search);
			$response = json_decode(file_get_contents('https://esi.evetech.net/latest/search/?categories=alliance&datasource=tranquility&language=en-us&search=' . $ammended . '&strict=true'), TRUE);

			if(isset($response['alliance'][0])) {
				$alliance_id = $response['alliance'][0];
			} else {

				$alliance_id = 2;

			}

			#$this->storeTypeID($response->inventory_type['0']);

			# Add this Character to the Database

			return $alliance_id;
		} catch (Exception $e) {

			return redirect()->back()
			->withErrors('Character Not Found');

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
				'alliance_alliance_id'     					=> $alliance_id,
			],[
				'alliance_creator_corporation_id'            => $response->creator_corporation_id,
				'alliance_creator_id'       					=> $response->creator_id,
				'alliance_date_founded'     					=> $response->date_founded,
				'alliance_executor_corporation_id'      		=> $response->executor_corporation_id,
				'alliance_name'              				=> $response->name,
				'alliance_ticker'            				=> $response->ticker,
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

