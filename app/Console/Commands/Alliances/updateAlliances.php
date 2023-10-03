<?php

namespace Vanguard\Console\Commands\Alliances;

use Illuminate\Console\Command;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

use Vanguard\Jobs;
use Vanguard\Jobs\Alliances\UpdateAllianceJob;
use Queue;

class updateAlliances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alliances:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update EVE Alliances & Corporations';

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

    		$esi = new Eseye();

    		$response = $esi->invoke('get', '/alliances/', []);

    		$bar = $this->output->createProgressBar(count($response));
    		$bar->start();

    		foreach ($response as $alliance) {


    			// For Each Alliance, Get Information 

    			//$this->updateAlliance($alliance);

    			Queue::push(new UpdateAllianceJob($alliance));   
    			$bar->advance();


    		}

    	}  catch (EsiScopeAccessDeniedException $e) {

    		$this->error('SSO Token is invalid');

    	} catch (RequestFailedException $e) {

    		$this->error('Got an ESI Error');

    	} catch (Exception $e) {

    		$this->error('ESI is fucked');
    	}

    	$bar->finish();


    }
}
