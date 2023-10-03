<?php

namespace Vanguard\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Vanguard\MoonScans;
use Vanguard\HarvestedMaterials;
use Vanguard\Moons;
use Log;


class parseMoonData implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $moon;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($moon)
    {
    	$this->moon = $moon;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

    	$sanitised = rtrim($this->moon, "\r");
    	$parsed = ltrim($sanitised, "\t");
    	list($product, $quantity, $ore_type_id, $solar_system_id, $planet_id, $moon_id) = explode("\t", $parsed);

    	$moon_info = Moons::where('moon_id', $moon_id)->first();
    	$ore = HarvestedMaterials::where('name', $product)->first();


    	$insert = MoonScans::updateOrCreate([
    		'moon_hash' 		=> md5($moon_id . $product . $solar_system_id),
    	],[
    		'moon_id' 			=> $moon_id,
    		'moon_name' 		=> $moon_info->moon_name,
    		'moon_system_id' 	=> $moon_info->moon_system_id,
    		'moon_system_name' 	=> $moon_info->moon_system_name,
    		'moon_product'		=> $product,
    		'moon_quantity'		=> $quantity,
    		'moon_ore_type_id'	=> $ore_type_id,				
    	]);

    	

    }
}