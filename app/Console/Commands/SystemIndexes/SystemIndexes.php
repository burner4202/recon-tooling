<?php

namespace Vanguard\Console\Commands\SystemIndexes;

use Illuminate\Console\Command;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

use Vanguard\Jobs;
use Vanguard\Jobs\SystemIndexes\UpdateSystemIndexesJob;
use Queue;

class SystemIndexes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'indexes:system:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets System Daily Indexes';

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
        $indices = $this->getIndices();

        foreach ($indices as $system_indices)
        {

            # Push to Queue

            Queue::push(new UpdateSystemIndexesJob($system_indices));   

        }
    }

    public function getIndices() {

        $configuration = Configuration::getInstance();

        $client_id = config('eve.client_id');
        $secret_key = config('eve.secret_key');

        try {

            $esi = new Eseye();
            $response = $esi->invoke('get', '/industry/systems/', []);


        }  catch (EsiScopeAccessDeniedException $e) {

            $this->error('SSO Token is invalid');

        } catch (RequestFailedException $e) {

            $this->error('Got an ESI Error');

        } catch (Exception $e) {

            $this->error('ESI is fucked');
        }

        return $response;

    }
}
