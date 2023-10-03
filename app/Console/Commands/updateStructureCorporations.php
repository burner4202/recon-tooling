<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;

use Vanguard\ESITokens;
use Vanguard\Characters;
use Vanguard\Alliances;
use Vanguard\Corporations;
use Vanguard\KnownStructures;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class updateStructureCorporations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:updateCorporations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Corporations';

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

    	$corporations = Corporations::orderBy('updated_at', 'DESC')->get();

    	$bar = $this->output->createProgressBar(count($corporations));
    	$bar->start();


    	foreach ($corporations as $corporation) {
    		## This has to be migrated to a Job/Redis Queue.
    		## Will also have to be chunked, as corporations grow, its eventually going to run out of memory.
    		//if($corporation->str_owner_corporation_id !== 0) { 
    			$bar->advance();
    			$this->updateCorporation($corporation->corporation_corporation_id);
    		//}
    	}

    	$bar->finish();    
    }

    public function updateCorporation($corporation_id)
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



    	} catch (RequestFailedException $e) {

    		$this->error('Got an ESI Error');

    		sleep(10);

    	} catch (Exception $e) {

    		$this->error('ESI is fucked');

    		sleep(10);
    	}


    }
}
