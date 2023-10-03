<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;

use Datetime;

use Auth;
use Log;
use Carbon\Carbon;
use Vanguard\User;
use Vanguard\ESITokens;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class updateESITokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:updateESITokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates all ESI Tokens in the database that are active.';

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

    	$tokens = ESITokens::where('esi_active', '=', '1')
    	->get();

    	$this->info('Checking Active ESI Keys for Validity');
    	$bar = $this->output->createProgressBar(count($tokens));
    	$bar->start();

    	foreach ($tokens as $token) {
    		$this->checkToken($token);
    		$bar->advance();
    	}

    	$bar->finish();

    }

    public function checkToken($token)
    {

    	$configuration = Configuration::getInstance();

    	$client_id = config('eve.client_id');
    	$secret_key = config('eve.secret_key');
    	$refresh_token = $token->esi_refresh_token;

    	$authentication = new EsiAuthentication([
    		'client_id'     => $client_id,
    		'secret'        => $secret_key,
    		'refresh_token' => $refresh_token,
    	]);

    	$character = $this->getCharacter($token->esi_character_id);

        if(!$character) {
            $updateESIToken = ESITokens::updateOrCreate([
                'esi_character_id'                => $token->esi_character_id,
            ],[
                'esi_active'                      => 0,
            ]);

        } else {

            $corporation = $this->getCorporation($character->corporation_id);

            $esi = new Eseye($authentication);

            try {

              $response = $esi->invoke('get', '/characters/{character_id}/location/', [
                 'character_id' => $token->esi_character_id,
             ]);


              $updateESIToken = ESITokens::updateOrCreate([
                 'esi_character_id'                => $token->esi_character_id,
             ],[
                 'esi_active'                      => 1,
                 'esi_character_name'			  => $character->name,
                 'esi_corporation_id'			  => $character->corporation_id,
                 'esi_corporation_name'			  => $corporation->name
             ]);

              $this->info(' ESI Token for ' . $token->esi_name . ' Valid');


          }  catch (EsiScopeAccessDeniedException $e) {

              $updateESIToken = ESITokens::updateOrCreate([
                 'esi_character_id'                => $token->esi_character_id,
             ],[
                 'esi_active'                      => 0,
                 'esi_character_name'			  => $character->name,
                 'esi_corporation_id'			  => $character->corporation_id,
                 'esi_corporation_name'			  => $corporation->name
             ]);

              $this->info(' ESI Token for ' . $token->esi_name . ' Set as Inactive');

          } catch (RequestFailedException $e) {


              $updateESIToken = ESITokens::updateOrCreate([
                 'esi_character_id'                => $token->esi_character_id,
             ],[
                 'esi_active'                      => 0,
                 'esi_character_name'			  => $character->name,
                 'esi_corporation_id'			  => $character->corporation_id,
                 'esi_corporation_name'			  => $corporation->name
             ]);

              $this->info(' ESI Token for ' . $token->esi_name . ' Set as Inactive');

          } catch (Exception $e) {

              $this->error(' ESI is fucked');
          }
      }

  }

  public function getCharacter($character_id) {

   $client_id = config('eve.client_id');
   $secret_key = config('eve.secret_key');

   $esi = new Eseye();

   try { 

      $character = $esi->invoke('get', '/characters/{character_id}/', [
         'character_id' => $character_id,
     ]);

  } catch (EsiScopeAccessDeniedException $e) {

  } catch (RequestFailedException $e) {

  } catch (Exception $e) {

  }

  return $character;

}

public function getCorporation($corporation_id) {

   $client_id = config('eve.client_id');
   $secret_key = config('eve.secret_key');

   $esi = new Eseye();

   try { 

      $corporation = $esi->invoke('get', '/corporations/{corporation_id}/', [
         'corporation_id' => $corporation_id,
     ]);


  } catch (EsiScopeAccessDeniedException $e) {

  } catch (RequestFailedException $e) {

  } catch (Exception $e) {

  }

  return $corporation;

}

}


