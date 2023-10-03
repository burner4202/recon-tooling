<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;

use Vanguard\KnownStructures;
use Vanguard\NewMoons;
use Vanguard\AllianceStandings;
use Carbon\Carbon;

class structuresHack extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:structuresHack';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Structure Hack - DO NOT RUN THIS, For backwards migrations of new columns';

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
    	# We only want to update structures that have a corporation.
    	$structures = KnownStructures::where('str_moon', '!=', "")->get();

        foreach($structures as $structure) {

            # Get the moon and update the rarity

            $structure->timestamps = false;

            #Get the Moon data

            $moon = NewMoons::where('moon_name', $structure->str_moon)->first();

            if($moon->moon_r_rating > 1) {
             $structure->str_moon_rarity = $moon->moon_r_rating;
         } else {
            $structure->str_moon_rarity = "";
        }
        $structure->save();
}
}



}
