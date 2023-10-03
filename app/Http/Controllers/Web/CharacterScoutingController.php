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
use Input;

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

class CharacterScoutingController extends Controller
{

    public function index() {

        return view('characters.import');
    }

    public function metadump()
    {

        $request = Input::all();

        if($request['title'] == null) {
            return redirect()->back()
            ->withErrors('Fill the box you dick.'); 
        }
        
        if($request['system'] == null) {
            return redirect()->back()
            ->withErrors('Need a system.'); 
        }

        if($request['alliance'] == null) {
            return redirect()->back()
            ->withErrors('Need an alliance.'); 
        }
        
        $alliance = Alliances::where('alliance_name', $request['alliance'])->first();
        $system = SolarSystems::where('ss_system_name', $request['system'])->first();

        if(!$alliance) { return redirect()->back()->withErrors('Alliance does not exist.'); }

        if(!$system) { return redirect()->back()->withErrors('System does not exist.'); }


        $lines = explode("\r\n", $request['title']);

        $test = array();

        foreach ($lines as $line) {

            if(strpos($line, '<url=showinfo:') == false) {
                return redirect()->back()
                ->withErrors('Invalid Parse Format.');
            }

            $exploded = explode("<url=showinfo:", $line);

            if(count($exploded) != 3) {
                  return redirect()->back()
                ->withErrors('Invalid Parse Format.');
            }

            $type_id_hull_id = explode("//", $exploded[1]);
            $type_id_hull = $type_id_hull_id[0];
            $parsed_hull_id = substr($type_id_hull_id[1], 0, 13);
            $hull_explode = explode(">", $type_id_hull_id[1]);
            $hull_name_explode = explode("</url", $hull_explode[1]);
            $hull_name = $hull_name_explode[0];

            $type_id_character_id = explode("//", $exploded[2]);
            $type_id_character = $type_id_character_id[0];
            $character_explode = explode(">", $type_id_character_id[1]);
            $character_name_explode = explode("</url", $character_explode[1]);
            $character_name = $character_name_explode[0];

            //dd($type_id_character_id, $character_name, $type_id_hull_id, $type_id_hull, $parsed_hull_id, $hull_name);     

            $character = Characters::where('character_name', $character_name)->first();

            if(!$character) {

                $character_id = $this->addCharacterToDatabase($character_name);

                if(!$character_id) {
                    return redirect()->route('character.index')->withErrors('This character does not exist in EVE online. Dummy.');
                }

            }
            $character = Characters::where('character_name', $character_name)->first();          
            if($this->iWantThisHull($type_id_hull)) {
                if($character) {

                # Hull Mapping
                    $titan = $character->titan;
                    $faction_titan = $character->faction_titan;
                    $super = $character->super;
                    $faction_super = $character->faction_super;
                    $carrier = $character->carrier;
                    $fax = $character->fax;
                    $dread = $character->dread;
                    $faction_dread = $character->faction_dread;
                    $monitor = $character->monitor;
                    $rorqual = $character->rorqual;
                    $freighter = $character->freighter;
                    $jump_freighter = $character->jump_freighter;
                    $cyno = $character->cyno;
                    $industrial_cyno = $character->industrial_cyno;


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

                if($this->checkIfTitan($type_id_hull)) { $titan = 1; $spotted_hull = "Titan"; }
                if($this->checkIfFactionTitan($type_id_hull)) { $faction_titan = 1; $spotted_hull = "Faction Titan"; }
                if($this->checkIfSuper($type_id_hull)) { $super = 1; $spotted_hull = "Super"; }
                if($this->checkIfFactionSuper($type_id_hull)) { $faction_super = 1; $spotted_hull = "Faction Super"; }
                if($this->checkIfCarrier($type_id_hull)) { $carrier = 1; $spotted_hull = "Carrier"; }
                if($this->checkIfFax($type_id_hull)) { $fax = 1; $spotted_hull = "Fax"; }
                if($this->checkifDread($type_id_hull)) { $dread = 1; $spotted_hull = "Dread"; }
                if($this->checkifFactionDread($type_id_hull)) { $faction_dread = 1; $spotted_hull = "Faction Dread"; }
                if($this->checkifMonitor($type_id_hull)) { $monitor = 1; $spotted_hull = "Monitor"; }
                if($this->checkifJumpFreighter($type_id_hull)) { $jump_freighter = 1; $spotted_hull = "Jump Freighter"; }
                if($this->checkifFreighter($type_id_hull)) { $freighter = 1; $spotted_hull = "Freighter"; }
                if($this->checkifRorqual($type_id_hull)) { $rorqual = 1; $spotted_hull = "Rorqual"; }

                $notes = "Added by scopehone import.";

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
                $report->alliance_id = $alliance->alliance_alliance_id;
                $report->alliance_name = $alliance->alliance_name;
                $report->hull_type = $spotted_hull;
                $report->ship_hull_id = $parsed_hull_id;
                $report->notes = $notes;
                $report->save();


                $update_character = Characters::updateOrCreate([
                    'character_character_id'            => $character->character_character_id
                ],[
                    'titan'                             => $titan,
                    'faction_titan'                     => $faction_titan,
                    'super'                             => $super,
                    'faction_super'                     => $faction_super,
                    'carrier'                           => $carrier,
                    'fax'                               => $fax,
                    'dread'                             => $dread,
                    'faction_dread'                     => $faction_dread,
                    'monitor'                           => $monitor,
                    'jump_freighter'                    => $jump_freighter,
                    'freighter'                         => $freighter,
                    'rorqual'                           => $rorqual,
                    'industrial_cyno'                   => $industrial_cyno,
                    'cyno'                              => $cyno,
                ]);
            }

        }




        return redirect()->back()
        ->withSuccess('Piece of Cake...');


    }



    /**
    * Looking for a Titan/Super/Carrier Hull.
    *
    * @return boolean
    */  
    public function iWantThisHull($type_id) {

        $hulls = [
            #Titans

            11567,   # Avatar
            3764,    # Levi
            671,     # Bus
            23773,   # Scrapmetal (Rag)

            # Faction Titans

            45649,   # Tanky Bitch (Komodo)
            42241,   # Molok, Heard INIT Sold One.
            42126,   # Vanquisher, I want one.

            # Supers

            23919,   # Aeon
            23917,   # Wyvern
            23913,   # Nyx
            22852,   # Hel

            # Carriers

            23757,   # Archon
            23915,   # Chimera
            23911,   # Thanatos
            24483,   # Nidhoggur

            # Faction Carriers

            3514,    # Revenant, I'm gay for Jay.
            42125,   # V for Vendetta

            # FAX

            37604,   # Apostle
            37605,   # Minokawa
            37607,   # Ninazu
            37606,   # Lif
            42242,   # Dagon
            45645,   # Loggerhead

            # Dreads

            19720,   # Revelation
            19726,   # Phoenix
            19724,   # Moros
            19722,   # Naglfar

            # Faction Dreads 

            45746,   # Caiman
            42243,   # Chemosh
            42124,   # Vehement

            # Monitor

            45534,   # Monitor

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

            11567,   # Avatar
            3764,    # Levi
            671,     # Bus
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

            45649,   # Tanky Bitch (Komodo)
            42241,   # Molok, Heard INIT Sold One.
            42126,   # Vanquisher, I want one
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

            23919,   # Aeon
            23917,   # Wyvern
            23913,   # Nyx
            22852,   # Hel

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
            42125,   # V for Vendetta

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

            23757,   # Archon
            23915,   # Chimera
            23911,   # Thanatos
            24483,   # Nidhoggur
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

            37604,   # Apostle
            37605,   # Minokawa
            37607,   # Ninazu
            37606,   # Lif
            42242,   # Dagon
            45645,   # Loggerhead

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

            19720,   # Revelation
            19726,   # Phoenix
            19724,   # Moros
            19722,   # Naglfar

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

            45746,   # Caiman
            42243,   # Chemosh
            42124,   # Vehement

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

            45534,   # Monitor
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
