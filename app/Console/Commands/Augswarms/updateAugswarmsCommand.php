<?php

namespace Vanguard\Console\Commands\Augswarms;

use Illuminate\Console\Command;

use Vanguard\Jobs\AugSwarms\updateCharacterInformationJob;
use Vanguard\Jobs\Characters\UpdateCharacterJob;
use Queue;

use Vanguard\AugswarmTracking;

class updateAugswarmsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'augswarms:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates all Augswarms locations/ship & logins.';

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

        $augswarms = AugswarmTracking::get();

        if($augswarms) {
            foreach($augswarms as $character) {
                # Dispatch Job to update.

               Queue::push(new updateCharacterInformationJob($character->at_character_id));   
           }

           $this->info('Dispatched ' . count($augswarms) . ' jobs to update characters.');
       } else {


        $this->info('No augswarms being currently tracked.');
    }
}
}
