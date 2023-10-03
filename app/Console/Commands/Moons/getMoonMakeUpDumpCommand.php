<?php

namespace Vanguard\Console\Commands\Moons;

use Illuminate\Console\Command;

use Vanguard\NewMoons;

class getMoonMakeUpDumpCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moons:data:dump';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Displays Moon Markup & Values in CSV Format.';

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

        /*
        "id" => 1
        "moon_id" => 40000004
        "moon_name" => "Tanoo I - Moon 1"
        "moon_system_id" => 30000001
        "moon_system_name" => "Tanoo"
        "moon_constellation_id" => 20000001
        "moon_constellation_name" => "San Matar"
        "moon_region_id" => 10000001
        "moon_region_name" => "Derelik"
        "created_at" => "2019-06-10 15:21:43"
        "updated_at" => "2019-06-10 15:21:43"
        "moon_r_rating" => ""
        "moon_dist_ore" => "null"
        "moon_extraction_values" => "null"
        "moon_ore_refine_value" => "null"
        "moon_value_24_hour" => 0.0
        "moon_value_7_day" => 0.0
        "moon_value_30_day" => 0.0
        "moon_atmo_gases" => 0
        "moon_cadmium" => 0
        "moon_caesium" => 0
        "moon_chromium" => 0
        "moon_cobalt" => 0
        "moon_dysprosium" => 0
        "moon_eva_depo" => 0
        "moon_hafnium" => 0
        "moon_hydrocarbons" => 0
        "moon_mercury" => 0
        "moon_neodymium" => 0
        "moon_platinum" => 0
        "moon_promethium" => 0
        "moon_scandium" => 0
        "moon_silicates" => 0
        "moon_technetium" => 0
        "moon_thulium" => 0
        "moon_titanium" => 0
        "moon_tungsten" => 0
        "moon_vanadium" => 0
        "moon_value_56_day" => 0.0
        */

        $moons = NewMoons::where('moon_value_30_day', '>', 1)
        ->where('moon_region_name', 'Delve')
        ->orWhere('moon_region_name', 'Querious')
        ->orWhere('moon_region_name', 'Fountain')
        ->orWhere('moon_region_name', 'Period Basis')
        ->where('moon_r_rating', 4)
        ->orderBy('moon_name')
        ->get();

        $this->info('Moon Name;Constellation Name;Region Name;Product Name;Rarity;Distribution;Units Per Hour');

        foreach($moons as $moon) {

            foreach(collect(json_decode($moon->moon_dist_ore)) as $type_id => $product) {
                
                $this->info($moon->moon_name . ';' . $moon->moon_constellation_name . ';' . $moon->moon_region_name . ';' . $product->name . ';' . $moon->moon_r_rating . ';' . $product->distribution * 100 . ';' . $product->units_per_hour);
            }

        }
    }
}
