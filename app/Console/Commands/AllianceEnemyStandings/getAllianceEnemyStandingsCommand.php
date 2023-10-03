<?php

namespace Vanguard\Console\Commands\AllianceEnemyStandings;

use Illuminate\Console\Command;

use Auth;
use DB;
use Vanguard\ESITokens;
use Vanguard\Characters;
use Vanguard\AllianceEnemyStandings;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class getAllianceEnemyStandingsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:getAllianceEnemyStandings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets Enemy Alliances Standings and stores to Database POW!';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
    	parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {


    	# Get all the characters that are in an alliance and group them up into a nice little package, so I have an ESI key for each alliance (including hostiles) muahahah.
    	$tokens = DB::table('esi_tokens')
    	->where('esi_active', 1)
    	->join('corporation', 'esi_tokens.esi_corporation_id', '=', 'corporation.corporation_corporation_id')
    	->join('alliances', 'corporation.corporation_alliance_id', '=', 'alliances.alliance_alliance_id')
    	->where('alliance_name', '!=', 'Goonswarm Federation')
    	->groupBy('alliance_name')
    	->get();


    	/*
 		"id" => 2
        "esi_user_id" => 116
        "esi_name" => "Hyela Plata"
        "esi_character_id" => 94026378
        "esi_avatar" => "https://image.eveonline.com/Character/94026378_128.jpg"
        "esi_token" => "*"
        "esi_refresh_token" => "*"
        "esi_scopes" => ""
        "esi_owner_hash" => "*
        "esi_active" => 1
        "created_at" => "2019-09-03 17:13:22"
        "updated_at" => "2020-06-10 09:12:33"
        "esi_character_name" => "Hyela Plata"
        "esi_corporation_id" => 98610369
        "esi_corporation_name" => "dude where's my citadel"
    	*/

        if($tokens) {

            AllianceEnemyStandings::truncate();

            foreach($tokens as $token) {

             $configuration = Configuration::getInstance();

             $client_id = config('eve.client_id');
             $secret_key = config('eve.secret_key');
             $refresh_token = $token->esi_refresh_token;

             $authentication = new EsiAuthentication([
              'client_id'     => $client_id,
              'secret'        => $secret_key,
              'refresh_token' => $refresh_token,
          ]);

             $esi = new Eseye($authentication);

             try {



              $response = $esi->invoke('get', '/alliances/{alliance_id}/contacts/', [
                'alliance_id' => $token->alliance_alliance_id,
            ]);

              $bar = $this->output->createProgressBar(count($response));
              $bar->start();




              foreach ($response as $contact) {   

               if($contact->contact_type == "character") {

                $character = $esi->invoke('get', '/characters/{character_id}/', [
                 'character_id' => $contact->contact_id,
             ]);

                $corporation = $esi->invoke('get', '/corporations/{corporation_id}/', [
                 'corporation_id' => $character->corporation_id,
             ]);

                if(isset($corporation->alliance_id)) {

                 $alliance = $esi->invoke('get', '/alliances/{alliance_id}/', [
                  'alliance_id' => $corporation->alliance_id,
              ]);

                 $alliance_id = $corporation->alliance_id;
                 $alliance_name = $alliance->name;
             } else {

                 $alliance_id = "";
                 $alliance_name = "";
             }

             $update = AllianceEnemyStandings::updateOrCreate([
                 'as_contact_id' 		=> $contact->contact_id,
             ],[
                 'as_contact_type' 	=> $contact->contact_type,
                 'as_standing' 	=> $contact->standing,
                 'as_enemy_alliance_id' => $token->alliance_alliance_id,
                 'as_enemy_alliance_name' => $token->alliance_name,
                 'as_character_name' => $character->name,
                 'as_corporation_id' => $character->corporation_id,
                 'as_corporation_name' =>$corporation->name,
                 'as_alliance_id' => $alliance_id,
                 'as_alliance_name' => $alliance_name,

             ]);

    				// End If
         }


         if($contact->contact_type == "corporation") {

            $corporation = $esi->invoke('get', '/corporations/{corporation_id}/', [
             'corporation_id' => $contact->contact_id,
         ]);

            if(isset($corporation->alliance_id)) {

             $alliance = $esi->invoke('get', '/alliances/{alliance_id}/', [
              'alliance_id' => $corporation->alliance_id,
          ]);

             $alliance_id = $corporation->alliance_id;
             $alliance_name = $alliance->name;
         } else {

             $alliance_id = "";
             $alliance_name = "";
         }

         $update = AllianceEnemyStandings::updateOrCreate([
             'as_contact_id' 		=> $contact->contact_id,
         ],[
             'as_contact_type' 	=> $contact->contact_type,
             'as_standing' 	=> $contact->standing,
             'as_enemy_alliance_id' => $token->alliance_alliance_id,
             'as_enemy_alliance_name' => $token->alliance_name,
             'as_corporation_id' => $contact->contact_id,
             'as_corporation_name' =>$corporation->name,
             'as_alliance_id' => $alliance_id,
             'as_alliance_name' => $alliance_name,

         ]);

    				// End If
     }

     if($contact->contact_type == "alliance") {

        $alliance = $esi->invoke('get', '/alliances/{alliance_id}/', [
         'alliance_id' => $contact->contact_id,
     ]);

        $alliance_id = $contact->contact_id;
        $alliance_name = $alliance->name;


        $update = AllianceEnemyStandings::updateOrCreate([
         'as_contact_id' 		=> $contact->contact_id,
     ],[
         'as_contact_type' 	=> $contact->contact_type,
         'as_standing' 	=> $contact->standing,
         'as_enemy_alliance_id' => $token->alliance_alliance_id,
         'as_enemy_alliance_name' => $token->alliance_name,
         'as_alliance_id' => $alliance_id,
         'as_alliance_name' => $alliance_name,

     ]);

    				// End If
    }

    $bar->advance();


    			// End Foreach

    $bar->finish();
}



}  catch (EsiScopeAccessDeniedException $e) {

  $this->error('SSO Token is invalid');

} catch (RequestFailedException $e) {

  $this->error('Got an ESI Error');

} catch (Exception $e) {

  $this->error('ESI is fucked');
}

}
}
}
}
