<?php

namespace Vanguard\Console\Commands\Moons;

use Illuminate\Console\Command;
use Vanguard\Moons;
use Vanguard\NewMoons;
use Vanguard\MoonCompare;

class getCompareMoons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moons:compare';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Moons, Compares 2017 to 2020 Data.';

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

 	## What do we want to do.
 	## 1. Get the new moon data.
 	## 2. Get the old moon data.
 	## 3. Check the total value & rarity of each moon.
 	## 4. Give a percentage difference in 56 day extraction value.


 	$this->info('Comparing Moon Data');
 	NewMoons::where('moon_value_24_hour', '>', 0)->chunk(100, function ($moons) {

    	//$bar = $this->output->createProgressBar(count($moon_scans));
    	//$bar->start();

 		foreach ($moons as $moon) {

 			$new_moon_data = $moon;

 			# Get the Old Moon data.

 			$old_moon_data = Moons::where('moon_name', $new_moon_data->moon_name)->first();

 			# Now we do some math and shit... i'm making this up as I go along so forgive me.
 			# 56 x for a full frack.

 			$new_moon_value = $new_moon_data->moon_value_24_hour * 56;
 			$old_moon_value = $old_moon_data->moon_value_24_hour * 56;

 			## Has the moon taken a hit. Calculate the percentage difference.

 			$moon_percentage_difference = (($new_moon_value - $old_moon_value) / (($new_moon_value + $old_moon_value)/2)) * 100;

 			if($old_moon_value) {

 				$moon_percentage_difference = (($new_moon_value - $old_moon_value) /  $old_moon_value) * 100;

 				$this->info('Moon Name : ' . $moon->moon_name . ' : New Value|Old Value : ' . number_format($new_moon_value,2) . ' | ' . number_format($old_moon_value,2) . ' : Percentage Change : ' . number_format($moon_percentage_difference,2) . '%');



 				$update_moon_data = MoonCompare::updateOrCreate([
 					'moon_name'                 			=> $moon->moon_name,
 				],[
 					'moon_id'                    			=> $moon->moon_id,
 					'moon_system_id'              			=> $moon->moon_system_id,
 					'moon_system_name'               	    => $moon->moon_system_name,
 					'moon_constellation_id'                 => $moon->moon_constellation_id,
 					'moon_constellation_name'               => $moon->moon_constellation_name,
 					'moon_region_id'              			=> $moon->moon_region_id,
 					'moon_region_name'               		=> $moon->moon_region_name,
 					'moon_old_r_rating'         		    => $old_moon_data->moon_r_rating,
 					'moon_new_r_rating'             		=> $new_moon_data->moon_r_rating,
 					'moon_old_value_56_day'                 => $old_moon_value,
 					'moon_new_value_56_day'                 => $new_moon_value,
 					'moon_percentage_difference'            => $moon_percentage_difference,


 				]);

 			}

		// END FOREACH
 		}
         // End Chunk

 		
 	});
		//$bar->finish();

 }
}
