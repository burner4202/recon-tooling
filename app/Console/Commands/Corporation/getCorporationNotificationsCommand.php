<?php

namespace Vanguard\Console\Commands\Corporation;

use Illuminate\Console\Command;

use Vanguard\Jobs;
use Vanguard\Jobs\CorporateNotifications\GetCorporationNotificationsJob;
use Queue;

use Carbon\Carbon;



class getCorporationNotificationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'corporation:update:notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the corporation assets notifications to check for destroyed/abandoned structures.';

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

        

        $x = 1;

        # List of characters to check;

        $characters = [
        94026378,   # Hyela Plata
        2118974045, # Swagger Extractor 1
        2118974060, # Swagger Extractor 2
        2118974066, # Swagger Extractor 3
        2118974046, # Swagger Extractor 4
        2118974071, # Swagger Extractor 5
        2118974076, # Swagger Extractor 6
        2118974047, # Swagger Extractor 7
        2118974078, # Swagger Extractor 8
        2118974084  # Swagger Extractor 9
        ];

        foreach($characters as $character) {
            $date = Carbon::now();
            
            $send_date = $date->addMinutes($x);

            $this->info($x . ' : ' . $character . ' Dispatch Date: ' . $send_date);
            
            Queue::later($send_date, new GetCorporationNotificationsJob($character));

            $x++;

        }

    }
}
