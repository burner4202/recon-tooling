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


class parseADASHData implements ShouldQueue
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

    	//Log::debug($this->moon);
    	
    	$sanitised = rtrim($this->moon, "\r\t");
    	$parsed = ltrim($sanitised, "\t\t");

    	list($region, $system, $planet, $moon, $product, $quantity) = explode("\t", $parsed);

    	$moon_info = Moons::where('moon_name', $moon)->first();
    	$ore = HarvestedMaterials::where('name', $product)->first();

		//$moonDetails = $this->getMoonDetails($moon_id);
		//$systemDetails = $this->getSystemDetails($solar_system_id);

    	$insert = MoonScans::updateOrCreate([
    		'moon_hash' 		=> md5($moon_info->moon_id . $product . $moon_info->moon_system_id),
    	],[
    		'moon_id' 			=> $moon_info->moon_id,
			'moon_name' 		=> $moon,
			'moon_system_id' 	=> $moon_info->moon_system_id,
			'moon_system_name' 	=> $moon_info->moon_system_name,
    		'moon_product'		=> $product,
    		'moon_quantity'		=> $quantity,
    		'moon_ore_type_id'	=> $ore->type_id,				
    	]);
		
    }
}