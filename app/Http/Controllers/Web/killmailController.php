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

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

use Vanguard\Jobs;
use Vanguard\Jobs\Characters\UpdateCharacterJob;
use Queue;



class killmailController extends Controller
{
	public function index() {

		$killmails = Killmails::get();
		$characters = Characters::get();
		$previous_killmails = Killmails::orderBy('created_at', 'DESC')->paginate(6);

		$hull_count = DB::table('characters')
		->select(
			DB::raw('character_alliance_name as character_alliance_name'),
			DB::raw('COUNT( (CASE WHEN titan = 1 THEN titan END) ) AS titan'),
			DB::raw('COUNT( (CASE WHEN faction_titan = 1 THEN faction_titan END) ) AS faction_titan'),
			DB::raw('COUNT( (CASE WHEN super = 1 THEN super END) ) AS supercarrier'),
			DB::raw('COUNT( (CASE WHEN faction_super = 1 THEN faction_super END) ) AS faction_supercarrier'),
			DB::raw('COUNT( (CASE WHEN carrier = 1 THEN carrier END) ) AS carrier'),
			DB::raw('COUNT( (CASE WHEN fax = 1 THEN fax END) ) AS fax'),
			DB::raw('COUNT( (CASE WHEN dread = 1 THEN dread END) ) AS dread'),
			DB::raw('COUNT( (CASE WHEN faction_dread = 1 THEN faction_dread END) ) AS faction_dread'),
			DB::raw('COUNT( (CASE WHEN monitor = 1 THEN monitor END) ) AS monitor')

		)
		->groupBy('character_alliance_name')
		->orderBy('character_alliance_name', 'ASC')
		->get();

		$chart_stacked = array();

		foreach($hull_count as $hull) {

			if($hull->character_alliance_name == "") { $alliance = "Not Part of Any Alliance"; } else { $alliance = $hull->character_alliance_name; }

			if($alliance !== "Goonswarm Federation") {

				$chart_stacked[$alliance] = [
					'titan' => $hull->titan,
					'faction_titan' => $hull->faction_titan,
					'supercarrier' => $hull->supercarrier,
					'faction_supercarrier' => $hull->faction_supercarrier,
					'carrier' => $hull->carrier,
					'fax' => $hull->fax,
					'dread' => $hull->dread,
					'faction_dread' => $hull->faction_dread,
					'monitor' => $hull->monitor,

				];
			}
		}

		return view('killmail.index', compact('killmails', 'characters', 'previous_killmails', 'characters', 'chart_stacked', 'hull_count'));


	}

	public function view_alliance($alliance_name) {

		$alliance = Characters::where('character_alliance_name', $alliance_name)->first();

		$alliance_name = $alliance->character_alliance_name;

		$characters = Characters::where('character_alliance_name', $alliance_name)->get();

		return view('killmail.view_alliance', compact('alliance_name', 'characters'));

	}

	public function storePost(Request $request) {

		$killmail_link = $request->input('killmail_link');

		if($killmail_link == null) {
			return redirect()->back()
			->withErrors('I need a killmail to process it.'); 
		}

		$url_check = explode('/', $killmail_link);

		# array:8 [▼
		#  0 => "https:"
		#  1 => ""
		#  2 => "esi.evetech.net"
		#  3 => "latest"
		#  4 => "killmails"
		#  5 => "74242689"
		#  6 => "8ff5f137b97c20a03c88e710d4864f269befc40f"
		#  7 => ""
		#]

		if($url_check[2] !== "esi.evetech.net") {
			return redirect()->back()
			->withErrors('Invalid URL Killmail'); 
		}


		if($url_check[4] !== "killmails") {
			return redirect()->back()
			->withErrors('Invalid Killmail'); 
		}

		# With all that validation crap out of the way.. could add more.. but meh..
		# Get killmail information.

		$killmail = collect(json_decode(file_get_contents($killmail_link), true));

		# - Return : All we are interested in, is the attackers. - we will use the killmail_id as our primary key.
		#	 #items: array:5 [▼
		#    "attackers" => array:123 [▶]
		#    "killmail_id" => 74242689
		#    "killmail_time" => "2018-12-27T01:18:12Z"
		#    "solar_system_id" => 30000772
		#    "victim" => array:6 [▶]

		# Primary Key
		$killmail_id = $killmail['killmail_id'];

		# Check to see if this killmail has already been parsed.
		/*
		$check = Killmails::where('killmail_id', $killmail_id)->first();

		if($check) {
			return redirect()->back()
			->withErrors('This killmail has already been parsed.'); 
		}
		*/
		

		# Save Killmail.
		$user = Auth::user();

		$add_killmail = Killmails::updateOrCreate([
			'killmail_id'           => $killmail_id
		],[
			'data'					=> json_encode($killmail),
			'added_by'				=> $user->username,
		]);

		
		# "alliance_id" => 99007379
		# "character_id" => 96550701
		# "corporation_id" => 98142344
		# "damage_done" => 3950038
		# "final_blow" => false
		# "security_status" => 2.4
		# "ship_type_id" => 23913
		# "weapon_type_id" => 40565

		$attackers = $killmail['attackers'];
		$victim = $killmail['victim'];

		# Check each attacker to see if their hull is i.e Titan/Super

		foreach ($attackers as $attacker) {

			# Make sure the attacker is in a ship. Cause CCP is bad.
			if(isset($attacker['ship_type_id'])) {

				$hull = $attacker['ship_type_id'];

				# Check if the pilot is flying a hull we want.
				if($this->iWantThisHull($hull)) {

				# Check if this pilot already exists

					$pilot = Characters::where('character_character_id', $attacker['character_id'])->first();

					if($pilot) {

					# Hull Mapping
						$titan = $pilot->titan;
						$faction_titan = $pilot->faction_titan;
						$super = $pilot->super;
						$faction_super = $pilot->faction_super;
						$carrier = $pilot->carrier;
						$fax = $pilot->fax;
						$dread = $pilot->dread;
						$faction_dread = $pilot->faction_dread;
						$monitor = $pilot->monitor;
						$rorqual = $pilot->rorqual;
						$freighter = $pilot->freighter;
						$jump_freighter = $pilot->jump_freighter;

					} else {

					# Hull Mapping
						$titan = 0;
						$faction_titan = 0;
						$super = 0;
						$faction_super = 0;
						$carrier = 0;
						$fax = 0;
						$dread = 0;
						$faction_dread = 0;
						$monitor = 0;
						$jump_freighter = 0;
						$freighter = 0;
						$rorqual = 0;

					}
				# Yes, they are, lets store it.

					# Map Character / Hull to Database

					if($this->checkIfTitan($hull)) { $titan = 1; }
					if($this->checkIfFactionTitan($hull)) { $faction_titan = 1; }
					if($this->checkIfSuper($hull)) { $super = 1; }
					if($this->checkIfFactionSuper($hull)) { $faction_super = 1; }
					if($this->checkIfCarrier($hull)) { $carrier = 1; }
					if($this->checkIfFax($hull)) { $fax = 1; }
					if($this->checkifDread($hull)) { $dread = 1; }
					if($this->checkifFactionDread($hull)) { $faction_dread = 1; }
					if($this->checkifMonitor($hull)) { $monitor = 1; }
					if($this->checkifJumpFreighter($hull)) { $jump_freighter = 1; }
					if($this->checkifFreighter($hull)) { $freighter = 1; }
					if($this->checkifRorqual($hull)) { $rorqual = 1; }

					$update_character = Characters::updateOrCreate([
						'character_character_id'            => $attacker['character_id']
					],[
						'titan'								=> $titan,
						'faction_titan'						=> $faction_titan,
						'super'								=> $super,
						'faction_super'						=> $faction_super,
						'carrier'							=> $carrier,
						'fax'								=> $fax,
						'dread'								=> $dread,
						'faction_dread'						=> $faction_dread,
						'monitor'							=> $monitor,
						'jump_freighter'					=> $jump_freighter,
						'freighter'							=> $freighter,
						'rorqual'							=> $rorqual,
					]);

					# Lets just throw a little job in there for good measure, if its cached, who gives a fuck.

					$character = Characters::where('character_character_id', $attacker['character_id'])->first();

					Queue::push(new UpdateCharacterJob($character));   

				}
			}
		}

		# Lets check the victim too, because why the fuck not.

		# Make sure the victim is in a ship. Cause CCP is bad.
		if(isset($victim['ship_type_id']) && isset($victim['character_id'])) {

			$hull = $victim['ship_type_id'];

			$pilot = Characters::where('character_character_id', $victim['character_id'])->first();

			if($pilot) {

				# Hull Mapping
				$titan = $pilot->titan;
				$faction_titan = $pilot->faction_titan;
				$super = $pilot->super;
				$faction_super = $pilot->faction_super;
				$carrier = $pilot->carrier;
				$fax = $pilot->fax;
				$dread = $pilot->dread;
				$faction_dread = $pilot->faction_dread;
				$monitor = $pilot->monitor;
				$rorqual = $pilot->rorqual;
				$freighter = $pilot->freighter;
				$jump_freighter = $pilot->jump_freighter;
				$cyno = $pilot->cyno;
				$industrial_cyno = $pilot->industrial_cyno;


			} else {

				# Hull Mapping
				$titan = 0;
				$faction_titan = 0;
				$super = 0;
				$faction_super = 0;
				$carrier = 0;
				$fax = 0;
				$dread = 0;
				$faction_dread = 0;
				$monitor = 0;
				$jump_freighter = 0;
				$freighter = 0;
				$rorqual = 0;
				$cyno = 0;
				$industrial_cyno = 0;

			}

			foreach($victim['items'] as $type_id) {

					# Check if there is a Cyno

				if($type_id['item_type_id'] == 52694) {
					$industrial_cyno = 1;
				}

					# Check if there is an Industrial Cyno

				if($type_id['item_type_id'] == 21096) {
					$cyno = 1;
				}
			}

			# Yes, they are, lets store it.
			# Map Character / Hull to Database

			if($this->checkIfTitan($hull)) { $titan = 1; }
			if($this->checkIfFactionTitan($hull)) { $faction_titan = 1; }
			if($this->checkIfSuper($hull)) { $super = 1; }
			if($this->checkIfFactionSuper($hull)) { $faction_super = 1; }
			if($this->checkIfCarrier($hull)) { $carrier = 1; }
			if($this->checkIfFax($hull)) { $fax = 1; }
			if($this->checkifDread($hull)) { $dread = 1; }
			if($this->checkifFactionDread($hull)) { $faction_dread = 1; }
			if($this->checkifMonitor($hull)) { $monitor = 1; }
			if($this->checkifJumpFreighter($hull)) { $jump_freighter = 1; }
			if($this->checkifFreighter($hull)) { $freighter = 1; }
			if($this->checkifRorqual($hull)) { $rorqual = 1; }

			$update_character = Characters::updateOrCreate([
				'character_character_id'            => $victim['character_id']
			],[
				'titan'								=> $titan,
				'faction_titan'						=> $faction_titan,
				'super'								=> $super,
				'faction_super'						=> $faction_super,
				'carrier'							=> $carrier,
				'fax'								=> $fax,
				'dread'								=> $dread,
				'faction_dread'						=> $faction_dread,
				'monitor'							=> $monitor,
				'jump_freighter'					=> $jump_freighter,
				'freighter'							=> $freighter,
				'rorqual'							=> $rorqual,
				'industrial_cyno'					=> $industrial_cyno,
				'cyno'								=> $cyno,
			]);

				# Lets just throw a little job in there for good measure, if its cached, who gives a fuck.

			$character = Characters::where('character_character_id', $victim['character_id'])->first();

			Queue::push(new UpdateCharacterJob($character));   

		}

		


		return redirect()->back()->withSuccess('Added Killmail to Queue for Parsing.');


	}

	/**
    * Looking for a Titan/Super/Carrier Hull.
    *
    * @return boolean
    */  
	public function iWantThisHull($type_id) {

		$hulls = [
			#Titans

			11567,	 # Avatar
			3764, 	 # Levi
			671, 	 # Bus
			23773,   # Scrapmetal (Rag)

			# Faction Titans

			45649, 	 # Tanky Bitch (Komodo)
			42241, 	 # Molok, Heard INIT Sold One.
			42126,	 # Vanquisher, I want one.

			# Supers

			23919, 	 # Aeon
			23917,	 # Wyvern
			23913, 	 # Nyx
			22852, 	 # Hel

			# Carriers

			23757, 	 # Archon
			23915, 	 # Chimera
			23911, 	 # Thanatos
			24483, 	 # Nidhoggur

			# Faction Carriers

			3514,    # Revenant, I'm gay for Jay.
			42125,	 # V for Vendetta

			# FAX

			37604,	 # Apostle
			37605,	 # Minokawa
			37607,	 # Ninazu
			37606, 	 # Lif
			42242,	 # Dagon
			45645,	 # Loggerhead

			# Dreads

			19720, 	 # Revelation
			19726, 	 # Phoenix
			19724, 	 # Moros
			19722, 	 # Naglfar

			# Faction Dreads 

			45746, 	 # Caiman
			42243,	 # Chemosh
			42124, 	 # Vehement

			# Monitor

			45534, 	 # Monitor

			# Jump Freighters

			28844,   # Rhea
			28846,   # Nomad
			28848,   # Anshar
			28850,   # Ark

			# Freighters

			20185,   # Charon
			20189,   # Fenrir
			20187,   # Obelisk
			20183,   # Providence

			# Rorqual

			28352,   # Rorqual

		];


		if (in_array($type_id, $hulls)) {
			return true;
		} else {
			return false;
		}
	}

	/**
    * Titan Check
    *
    * @return boolean
    */  
	public function checkIfTitan($type_id) {

		$hulls = [
			#Titans

			11567,	 # Avatar
			3764, 	 # Levi
			671, 	 # Bus
			23773,   # Scrapmetal (Rag)

		];


		if (in_array($type_id, $hulls)) {
			return true;
		} else {
			return false;
		}
	}

	/**
    * Looking for a Faction Titan
    *
    * @return boolean
    */  
	public function checkIfFactionTitan($type_id) {

		$hulls = [
			# Faction Titans

			45649, 	 # Tanky Bitch (Komodo)
			42241, 	 # Molok, Heard INIT Sold One.
			42126,	 # Vanquisher, I want one
		];


		if (in_array($type_id, $hulls)) {
			return true;
		} else {
			return false;
		}
	}

	/**
    * Looking for a Super
    *
    * @return boolean
    */  
	public function checkIfSuper($type_id) {

		$hulls = [
			# Supers

			23919, 	 # Aeon
			23917,	 # Wyvern
			23913, 	 # Nyx
			22852, 	 # Hel

		];


		if (in_array($type_id, $hulls)) {
			return true;
		} else {
			return false;
		}
	}

	/**
    * Looking for a Faction Super
    *
    * @return boolean
    */  
	public function checkIfFactionSuper($type_id) {

		$hulls = [
			# Faction Carriers

			3514,    # Revenant, I'm gay for Jay.
			42125,	 # V for Vendetta

		];


		if (in_array($type_id, $hulls)) {
			return true;
		} else {
			return false;
		}
	}

	/**
    * Looking for a Carrier
    *
    * @return boolean
    */  
	public function checkIfCarrier($type_id) {

		$hulls = [
			# Carriers

			23757, 	 # Archon
			23915, 	 # Chimera
			23911, 	 # Thanatos
			24483, 	 # Nidhoggur
		];


		if (in_array($type_id, $hulls)) {
			return true;
		} else {
			return false;
		}
	}

	/**
    * Looking for a Fax
    *
    * @return boolean
    */  
	public function checkIfFax($type_id) {

		$hulls = [
			# FAX

			37604,	 # Apostle
			37605,	 # Minokawa
			37607,	 # Ninazu
			37606, 	 # Lif
			42242,	 # Dagon
			45645,	 # Loggerhead

		];


		if (in_array($type_id, $hulls)) {
			return true;
		} else {
			return false;
		}
	}

	/**
    * Looking for a Dread
    *
    * @return boolean
    */  
	public function checkifDread($type_id) {

		$hulls = [
			# Dreads

			19720, 	 # Revelation
			19726, 	 # Phoenix
			19724, 	 # Moros
			19722, 	 # Naglfar

		];


		if (in_array($type_id, $hulls)) {
			return true;
		} else {
			return false;
		}
	}

	/**
    * Looking for a Faction Dread
    *
    * @return boolean
    */  
	public function checkIfFactionDread($type_id) {

		$hulls = [

			# Faction Dreads 

			45746, 	 # Caiman
			42243,	 # Chemosh
			42124, 	 # Vehement

		];


		if (in_array($type_id, $hulls)) {
			return true;
		} else {
			return false;
		}
	}

	/**
    * Looking for a Monitor
    *
    * @return boolean
    */  
	public function checkifMonitor($type_id) {

		$hulls = [

			# FC Ship

			45534, 	 # Monitor
		];


		if (in_array($type_id, $hulls)) {
			return true;
		} else {
			return false;
		}
	}

	/**
    * Looking for a Jump Freighter
    *
    * @return boolean
    */  
	public function checkifJumpFreighter($type_id) {

		$hulls = [

			# Jump Freighters

			28844,   # Rhea
			28846,   # Nomad
			28848,   # Anshar
			28850,   # Ark
		];


		if (in_array($type_id, $hulls)) {
			return true;
		} else {
			return false;
		}
	}

	/**
    * Looking for a Freighter
    *
    * @return boolean
    */  
	public function checkifFreighter($type_id) {

		$hulls = [

			# Freighters

			20185,   # Charon
			20189,   # Fenrir
			20187,   # Obelisk
			20183,   # Providence
		];


		if (in_array($type_id, $hulls)) {
			return true;
		} else {
			return false;
		}
	}

	/**
    * Looking for a Rorqual
    *
    * @return boolean
    */  
	public function checkifRorqual($type_id) {

		$hulls = [

			# Rorqual

			28352,   # Rorqual
		];


		if (in_array($type_id, $hulls)) {
			return true;
		} else {
			return false;
		}
	}

}
