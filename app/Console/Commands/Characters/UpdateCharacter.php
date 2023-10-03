<?php

namespace Vanguard\Console\Commands\Characters;

use Illuminate\Console\Command;

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

use Vanguard\Characters;

class UpdateCharacter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'characters:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Characters Corporation/Alliance';

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
      $characters = Characters::orderBy('updated_at', 'DESC')->get();
      $bar = $this->output->createProgressBar(count($characters));
      $bar->start();

      foreach ($characters as $character) {

        Queue::push(new UpdateCharacterJob($character));   
        $bar->advance();

    }
    $bar->finish();
}
}
