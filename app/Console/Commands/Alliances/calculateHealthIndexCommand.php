<?php

namespace Vanguard\Console\Commands\Alliances;

use Illuminate\Console\Command;
use Vanguard\SovStructures;
use Vanguard\AllianceHealthIndex;

use Carbon\Carbon;

class calculateHealthIndexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alliances:health';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculates an Alliances Health based on Sov and ADMs';

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

    	$now = Carbon::now();

        # Get all alliances
    	$alliances = SovStructures::groupBy('alliance_name')
    	->where('structure_type_name', '=', 'Infrastructure Hub')
    	->where('vulnerability_occupancy_level', '>', '0')
    	->get();

    	foreach($alliances as $alliance) {

            # Get alliances sov and calculate it.
            /*
            $health = $this->calculateHealth($alliance->alliance_id);

            dd($health);
            */;

            $adm = SovStructures::where('alliance_id', $alliance->alliance_id)
            ->where('structure_type_name', '=', 'Infrastructure Hub')
            ->where('vulnerability_occupancy_level', '>', '0')
            ->get();

            $sum_of_adm = $adm->sum('vulnerability_occupancy_level');
            $average_adm = $adm->average('vulnerability_occupancy_level');
            $systems = count($adm);
            $health = $sum_of_adm / ($systems * 6);
            $ranking = (($health / $systems) * 100);
            //$ranking = $health * ($systems / count($alliances)) * 100;

            $this->info($alliance->alliance_name . ',' . number_format($health * 100,2) . ',' . $systems . ',' . number_format($average_adm,2) . ',' . number_format($ranking,2)); 
            
            $update = AllianceHealthIndex::updateOrCreate([
            	'key'                                        => $alliance->alliance_id . '+' . $now->format('Y-m-d'),
            ],[
            	'alliance_id'                                => $alliance->alliance_id,
            	'alliance_name'                              => $alliance->alliance_name,
            	'alliance_ticker'                            => $alliance->alliance_ticker,
            	'ihub_count'                                 => $systems,
            	'health'                                     => $health * 100,
            	'average_adm'                                => $average_adm,
            	'date'                                       => $now->format('Y-m-d'),
            ]);
            

        }



    }

    private function calculateHealth($alliance_id) {

        /*
        ‘Static; Longevity Formula, What state an alliance is in during peacetime and without the daily loss of ihubs.’
        (sum(ADM)/(Systems*6))
        */

        $health = SovStructures::where('alliance_id', $alliance_id)->get();


        

    }
}
