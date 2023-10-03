<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;

use Auth;
use Vanguard\ESITokens;
use Vanguard\Characters;
use Vanguard\AllianceStandings;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;


class getCharacterInformation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:getCharacterInformation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Character Information';

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

    	$configuration = Configuration::getInstance();

    	$client_id = config('eve.client_id');
    	$secret_key = config('eve.secret_key');

    	try {

    		$tokens = ESITokens::where('esi_active', '=', '1')
    		->get();

    		foreach ($tokens as $character) {

    			$esi = new Eseye();

    			$response = $esi->invoke('get', '/characters/{character_id}/', [
    				'character_id' => $character->esi_character_id,
    			]);

    			$character = Characters::updateOrCreate([
    				'character_character_id' 		=> $character->esi_character_id,
    			],[
    				'character_corporation_id' 	=> $response->corporation_id,
    				'character_birthday' 			=> $response->birthday,
    				'character_name' 				=> $response->name,
    				'character_security_status'   => $response->security_status,
    			]);

    			$this->info('Characters Updated: ' . $response->name);
    		}


    	}  catch (EsiScopeAccessDeniedException $e) {

    		$this->error('SSO Token is invalid');

    	} catch (RequestFailedException $e) {

    		$this->error('Got an ESI Error' . $e);

    	} catch (Exception $e) {

    		$this->error('ESI is fucked');
    	}

    }
}


