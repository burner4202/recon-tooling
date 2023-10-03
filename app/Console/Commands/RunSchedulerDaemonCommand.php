<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;

class RunSchedulerDaemonCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:daemon {--sleep=60}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starts a daemon that will automatically run the Laravel schedule every 60 seconds';

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
    public function handle(): void
    {
    	while (true) {
    		$this->call('schedule:run');
    		sleep($this->option('sleep'));
    	}
    }
}
