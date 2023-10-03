<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;

use Vanguard\Moons;
use Vanguard\NewMoons;
use Vanguard\MoonScans;



class PurgeNewMoons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purge:moons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purge Command for 2020 Moons, NO NOT RUN ME MORE THAN ONCE';

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

    	$this->info('Purging Moon Goo Data');
    	MoonScans::groupBy('moon_id')->orderBy('updated_at', 'DESC')->chunk(100, function ($moon_scans) {


    		foreach ($moon_scans as $moon) {

    			$atmo_gases = 0;
    			$cadmium = 0;
    			$caesium = 0;
    			$chromium = 0;
    			$cobalt = 0;
    			$dysprosium = 0;
    			$eva_depo = 0;
    			$hafnium = 0;
    			$hydrocarbons = 0;
    			$mercury = 0;
    			$neodymium = 0;
    			$platinum = 0;
    			$promethium = 0;
    			$scandium = 0;
    			$silicates = 0;
    			$technetium = 0;
    			$thulium = 0;
    			$titanium = 0;
    			$tungsten = 0;
    			$vanadium = 0;


    			$update_moon = NewMoons::where('moon_id', $moon->moon_id)->first();

    			$update_moon->moon_r_rating = null;
    			$update_moon->moon_dist_ore = null;
    			$update_moon->moon_value_24_hour = 0;
    			$update_moon->moon_value_7_day = 0;
    			$update_moon->moon_value_30_day = 0;
    			$update_moon->moon_atmo_gases = $atmo_gases;
    			$update_moon->moon_cadmium = $cadmium;
    			$update_moon->moon_caesium = $caesium;
    			$update_moon->moon_chromium = $chromium;
    			$update_moon->moon_cobalt = $cobalt;
    			$update_moon->moon_dysprosium = $dysprosium;
    			$update_moon->moon_eva_depo = $eva_depo;
    			$update_moon->moon_hafnium = $hafnium;
    			$update_moon->moon_hydrocarbons = $hydrocarbons;
    			$update_moon->moon_mercury = $mercury;
    			$update_moon->moon_neodymium = $neodymium;
    			$update_moon->moon_platinum = $platinum;
    			$update_moon->moon_promethium = $promethium;
    			$update_moon->moon_scandium = $scandium;
    			$update_moon->moon_silicates = $silicates;
    			$update_moon->moon_technetium = $technetium;
    			$update_moon->moon_thulium = $thulium;
    			$update_moon->moon_titanium = $titanium;
    			$update_moon->moon_tungsten = $tungsten;
    			$update_moon->moon_vanadium = $vanadium;

    			$update_moon->save();

    			$this->info('Moon Name: ' . $update_moon->moon_name . ' : Purged');

    		}

    	});


    }


}
