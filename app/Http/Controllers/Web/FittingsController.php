<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Auth;
use Input;
use DB;
use Carbon\Carbon;
use Vanguard\User;
use Vanguard\ESITokens;
use Vanguard\SolarSystems;
use Vanguard\TypeIDs;
use Vanguard\Alliances;
use Vanguard\Corporations;
use Vanguard\Killmails;
use Vanguard\Characters;
use Vanguard\Fittings;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class FittingsController extends Controller
{
	public function index() {

		$fittings = Fittings::sortable()->orderBy('created_at', 'ASC')->paginate(10);
		
		return view('fittings.index', compact('fittings'));
	}

	public function view($id) {

		$fitting = Fittings::where('id', $id)->first();

		$modules = json_decode($fitting->fitting_modules);
		$cargo = json_decode($fitting->fitting_cargo);

		return view('fittings.view', compact('fitting', 'modules', 'cargo'));
	}

	public function storeFitting(Request $request) {

		$fitting = $request->input('fitting');

		if($fitting == null) {
			return redirect()->back()
			->withErrors('I need a fitting to process it.'); 
		}

		/*

		"""
		[Jackdaw, Simulated Jackdaw Fitting]\r\n
		Micro Auxiliary Power Core II\r\n
		Ballistic Control System II\r\n
		Power Diagnostic System II\r\n
		\r\n
		5MN Y-T8 Compact Microwarpdrive\r\n
		Republic Fleet Medium Shield Extender\r\n
		EM Ward Field II\r\n
		Republic Fleet Medium Shield Extender\r\n
		Adaptive Invulnerability Field II\r\n
		\r\n
		TE-2100 Ample Light Missile Launcher\r\n
		TE-2100 Ample Light Missile Launcher\r\n
		TE-2100 Ample Light Missile Launcher\r\n
		TE-2100 Ample Light Missile Launcher\r\n
		TE-2100 Ample Light Missile Launcher\r\n
		\r\n
		Small Core Defense Field Extender II\r\n
		Small Hydraulic Bay Thrusters II\r\n
		Small Core Defense Field Extender II\r\n
		\r\n
		\r\n
		\r\n
		\r\n
		Caldari Navy Scourge Light Missile x1000\r\n
		Caldari Navy Inferno Light Missile x1000\r\n
		Defender Missile I x100\r\n
		Nova Fury Light Missile x1000\r\n
		Missile Range Script x1\r\n
		Missile Precision Script x1\r\n
		Caldari Navy Nova Light Missile x2000\r\n
		Caldari Navy Mjolnir Light Missile x2265\r\n
		Mjolnir Fury Light Missile x1000
		"""
		*/

		## Remove \r
		$remove_eof = str_replace("\r", "", $fitting);

		## Explode into array by \n
		$explode = explode("\n", $remove_eof);

		## Check the first line is actualy a fit.
		## Pattern

		$pattern = "^\[(.*?)\]^";

		$fitting_name =  preg_match($pattern, $explode[0], $name);

		if(!isset($name[0])) {

			return redirect()->back()->withErrors('Invalid EFT Import.');
		}

		if($name[0] === "") {
			# [Fitting Name] is false, actual fit.
			# Redirect for invalid fit.
			dd('Fitting Name is incorrect.');
		}

		# Get the Hull Type/Name/Fitting Name
		$hull = explode(", ", $name[1]);
		$hull_name = $hull[0];
		$fitting_name = $hull[1];

		# Check Cache If it Exists, If Not Search CCP.
		$hull_exists = TypeIDs::where('ti_name', $hull_name)->first();

		if($hull_exists) {
			# It Exists, Add Type ID
			$hull_type_id = $hull_exists->ti_type_id;

		} else {
			# Search CCP
			$hull_type_id = $this->searchEVETypeID($hull_name);

		}

		$hull_value = $this->getMarketPrice($hull_type_id);

		## Remove White Space
		$remove_whitespace = array_filter(array_map('trim', $explode));
		
		## Remove Fitting Bracketted Name Prior to Searching and Mapping Fitting.
		array_shift($remove_whitespace);

		## Remove Garbage

		$delete_val = array(
			"[Empty High slot]"
		);

		$sanitisedFitting = array_diff($remove_whitespace, $delete_val);

		# Go through each line of the fit.
		# Init Arrays

		$cargo = array();
		$modules = array();
		$parsed_fitting = array();
		$total_value = 0;
		$module_value = 0;
		$cargo_value = 0;

		foreach($sanitisedFitting as $line) {

			## Filter the Cargo.

			$filter_cargo = explode(" x", $line);

			## Check if the line is actually cargo.
			if(count($filter_cargo) > 1) {

				# Check Database to see if ammo exists in cache.
				$ammo_check = TypeIDs::where('ti_name', $filter_cargo[0])->first();

				if($ammo_check) {

					# Get Price from CCP.
					$average_price = $this->getMarketPrice($ammo_check->ti_type_id);

					#Compile array with Type ID / Name / Amount
					$cargo[] =
					[
						'name'		 => $filter_cargo[0],
						'type_id'	 => $ammo_check->ti_type_id,
						'amount'	 => $filter_cargo[1],
						'price'		 => $average_price * $filter_cargo[1],
					];

					$cargo_value += $average_price;

					# END OF IF
				} else {

					# Get Type ID from CCP and add it to the database.
					$response = $this->searchEVETypeID($filter_cargo[0]);

					# Get Price from CCP.
					$average_price = $this->getMarketPrice($response);

					#Compile array with Type ID / Name / Amount
					$cargo[] =
					[
						'name'		 => $filter_cargo[0],
						'type_id'	 => $response,
						'amount'	 => $filter_cargo[1],
						'price'		 => $average_price * $filter_cargo[1],
					];

					$cargo_value += $average_price;

					# END OF ELSE
					
				}

				
				# END OF CARGO CHECK
				
			} else {

				# Filtered Cargo, now we should do the modules.

				# Check Database to see the module exists
				$module_check = TypeIDs::where('ti_name', $filter_cargo[0])->first();

				if($module_check) {

					# Get Price from CCP.
					$average_price = $this->getMarketPrice($module_check->ti_type_id);

					#Compile array with Type ID / Name / Amount
					$modules[] =
					[
						'name'		 => $filter_cargo[0],
						'type_id'	 => $module_check->ti_type_id,
						'slot'		 => $module_check->ti_slot,
						'price'		 => $average_price,
					];

					$module_value += $average_price;

					# END OF IF
				} else {

					# Get Type ID from CCP and add it to the database.
					$response = $this->searchEVETypeID($filter_cargo[0]);

					# Recall Database Entry for Meta Data.
					$module_check = TypeIDs::where('ti_name', $filter_cargo[0])->first();

					# Get Price from CCP.
					$average_price = $this->getMarketPrice($module_check->ti_type_id);

					#Compile array with Type ID / Name / Amount
					$modules[] =
					[
						'name'		 => $filter_cargo[0],
						'type_id'	 => $module_check->ti_type_id,
						'slot'		 => $module_check->ti_slot,
						'price'		 => $average_price,
					];

					$module_value += $average_price;

					# END OF ELSE
					
				}

				# END OF MODULES

			}

			//print_r($line . '<br>');
		}

		# Fitting has been added and stored. Package it up and saving to the fitting database.

		$total_value = $hull_value + $module_value + $cargo_value;

		$user = Auth::user();

		$fitting = new Fittings;
		$fitting->fitting_name      	= $fitting_name;
		$fitting->fitting_hull_name		= $hull_name;
		$fitting->fitting_hull_type_id  = $hull_type_id;
		$fitting->fitting_hull_value 	= $hull_value;
		$fitting->fitting_modules   	= json_encode($modules);
		$fitting->fitting_module_value 	= $module_value;
		$fitting->fitting_cargo     	= json_encode($cargo);
		$fitting->fitting_cargo_value 	= $cargo_value;
		$fitting->fitting_value 		= $total_value;
		$fitting->fitting_added_by  	= $user->username;
		$fitting->save();

		return redirect()->back()->withSuccess('Fitting Added.');


	}


	public function searchEVETypeID($search)
	{
		try {
			$ammended = str_replace(" ", "%20", $search);
			$response = json_decode(file_get_contents('https://esi.evetech.net/latest/search/?categories=inventory_type&datasource=tranquility&language=en-us&search=' . $ammended . '&strict=true'));

			# Cache TypeID
			$this->storeTypeID($response->inventory_type['0']);

			return $response->inventory_type['0'];
		} catch (Exception $e) {

			return redirect()->back()
			->withErrors('Invalid Fitting');

		}
	}

	public function storeTypeID($type_id) { // End Point: https://esi.evetech.net/ui/#/Universe/get_universe_types_type_id

		$configuration = Configuration::getInstance();

		$client_id = config('eve.client_id');
		$secret_key = config('eve.secret_key');

		$esi = new Eseye();

		try { 

			$response = $esi->invoke('get', '/universe/types/{type_id}/', [   
				'type_id' => $type_id,
			]);

			# Add to Database.

			$slot = "";
			$dogma_effects = json_encode("");
			$dogma_attributes = json_encode("");

			if(isset($response->dogma_attributes)) { $dogma_attributes = json_encode($response->dogma_attributes); }

			if(isset($response->dogma_effects)) {

				$dogma_effects = json_encode($response->dogma_effects);

				foreach ($response->dogma_effects as $fitting_slot) {

					if($fitting_slot->effect_id == 11) {
					// Low Power Slot Needed
						$slot = "3 - Low";
					}

					if($fitting_slot->effect_id == 12) {
					// High Power Slot Needed
						$slot = "1 - High";
					}

					if($fitting_slot->effect_id == 13) {
					// Medium Power Slot Needed
						$slot = "2 - Medium";
					}

					if($fitting_slot->effect_id == 2663) {
					// Rig Slot Needed
						$slot = "4 - Rig";
					}

					if($fitting_slot->effect_id == 3772) {
					// Rig Slot Needed
						$slot = "5 - Subsystem";
					}

				}	

			}		

			$update = TypeIDs::updateOrCreate([
				'ti_type_id'      				=> $type_id,
			],[
				'ti_name'  						=> $response->name,
				'ti_description'  				=> $response->description,
				'ti_dogma_attributes'  			=> $dogma_attributes,
				'ti_dogma_effects'  			=> $dogma_effects,
				'ti_group_id'  					=> $response->group_id,
				'ti_market_group_id'  			=> $response->market_group_id,
				'ti_slot'						=> $slot,
			]);




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


		return $response;
	}

	public function getMarketPrice($id) {

		$marketSearch = collect(json_decode(file_get_contents('https://esi.evetech.net/v1/markets/10000002/history/?type_id=' . $id), true));
		//$value = $marketSearch->where('date', Carbon::now()->subDay()->toDateString());

		$average_value = $marketSearch->take(30)->avg('average');

		return $average_value;
	}



}
