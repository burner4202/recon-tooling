<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;

use Vanguard\KnownStructures;

class cleanUpStructures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'structure:clean:up';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes Stale Structures form the DB';

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
    	$count = KnownStructures::where('str_owner_corporation_id', 0)->count();

    	$this->info('Found ' . $count . ' Stale Structures');

    	if($count) {

    		$stale = KnownStructures::where('str_owner_corporation_id', 0)->delete();

    		$this->info('Structure Database Cleaned.');

    	} else {
    		$this->info('Structure Database is Clean.');
    	}
    }

}
