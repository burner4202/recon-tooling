<?php

namespace Vanguard\Jobs\SystemIndexes;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Carbon\Carbon;
use Vanguard\SolarSystems;
use Vanguard\SystemCostIndices;

class UpdateSystemIndexesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $system_indices;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($system_indices)
    {
        $this->system_indices = $system_indices;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $now = Carbon::now();
        $yesterday = Carbon::now()->subDay(1);
        $solar_system_id = $this->system_indices->solar_system_id;
        $system = SolarSystems::where('ss_system_id', $solar_system_id)->first();

        $indexes = $this->system_indices->cost_indices;
        $manufacturing = $indexes['0']->cost_index;
        $researching_time_efficiency = $indexes['1']->cost_index;
        $researching_material_efficiency = $indexes['2']->cost_index;
        $copying = $indexes['3']->cost_index;
        $invention = $indexes['4']->cost_index;
        $reaction = $indexes['5']->cost_index;
        $key = $solar_system_id . '-' .  $now->format('Y-m-d');
        
        # Calculate the Delta.

        # (final / initial) * 100

        $inital = SystemCostIndices::where('sci_solar_system_id', $solar_system_id)
        ->where('sci_date', $yesterday->format('Y-m-d'))
        ->first();

        if($inital) {

        # Found yesterdays records, lets calculate the delta

            $sci_manufacturing_delta                        = (($manufacturing - $inital->sci_manufacturing) / $inital->sci_manufacturing * 100);
            $sci_researching_time_efficiency_delta          = (($researching_time_efficiency - $inital->sci_researching_time_efficiency) / $inital->sci_researching_time_efficiency * 100);
            $sci_researching_material_efficiency_delta      = (($researching_material_efficiency - $inital->sci_researching_material_efficiency) / $inital->sci_researching_material_efficiency * 100);
            $sci_copying_delta                              = (($copying - $inital->sci_copying) / $inital->sci_copying * 100);
            $sci_invention_delta                            = (($invention - $inital->sci_invention) / $inital->sci_invention * 100);
            $sci_reaction_delta                             = (($reaction - $inital->sci_reaction) / $inital->sci_reaction * 100);

        //$this->info($manufacturing);
        //$this->info($inital->sci_manufacturing);

        } else {

            $sci_manufacturing_delta = 0;
            $sci_researching_time_efficiency_delta = 0;
            $sci_researching_material_efficiency_delta = 0;
            $sci_copying_delta = 0;
            $sci_invention_delta = 0;
            $sci_reaction_delta = 0;
        }

        //$this->info($sci_manufacturing_delta);


        $update = SystemCostIndices::updateOrCreate([
            'sci_key'                                    => $key,
        ],[
            'sci_solar_system_id'                        => $system->ss_system_id,
            'sci_solar_system_name'                      => $system->ss_system_name,
            'sci_solar_constellation_id'                 => $system->ss_constellation_id,
            'sci_solar_constellation_name'               => $system->ss_constellation_name,
            'sci_solar_region_id'                        => $system->ss_region_id,
            'sci_solar_region_name'                      => $system->ss_region_name,
            'sci_manufacturing'                          => $manufacturing,
            'sci_researching_time_efficiency'            => $researching_time_efficiency,
            'sci_researching_material_efficiency'        => $researching_material_efficiency,
            'sci_copying'                                => $copying,
            'sci_invention'                              => $invention,
            'sci_reaction'                               => $reaction,
            'sci_date'                                   => $now->format('Y-m-d'),
            'sci_security_status'                        => $system->ss_security_status,
            'sci_manufacturing_delta'                    => $sci_manufacturing_delta,
            'sci_researching_time_efficiency_delta'      => $sci_researching_time_efficiency_delta,
            'sci_researching_material_efficiency_delta'  => $sci_researching_material_efficiency_delta,
            'sci_copying_delta'                          => $sci_copying_delta,
            'sci_invention_delta'                        => $sci_invention_delta,
            'sci_reaction_delta'                         => $sci_reaction_delta,


        ]);

        ## If the reaction index delta is greater than 2000% post to jabber.

        if($sci_reaction_delta > 1500 && $system->ss_security_status < 0.5) {
            //$this->info('Sending to Jabber');
            $content = $system->ss_system_name . ' ('.$system->ss_security_status.') in ' . $system->ss_region_name . ' has had a ' . number_format($sci_reaction_delta,2) . '%' . ' increase in reaction activity. Index currently at: ' . $reaction * 100 . '% ' . url('/system/' . $system->ss_system_id);
            $this->postToJabber($content);

        }
        ## If the manufacturing index delta is greater than 900% post to jabber.

        if($sci_manufacturing_delta > 1500 && $system->ss_security_status < 0.5) {
            //$this->info('Sending to Jabber');
            $content = $system->ss_system_name . ' ('.$system->ss_security_status.') in ' . $system->ss_region_name . ' has had a ' . number_format($sci_manufacturing_delta,2) . '%' . ' increase in manufacturing activity. Index currently at: ' . $manufacturing * 100 . '% ' . url('/system/' . $system->ss_system_id);
            $this->postToJabber($content);
        }

    }

    public function postToJabber($content) {

        $channel = 'rt@conference.goonfleet.com';
                # I don't care about errors.
        $client = new \GuzzleHttp\Client(['http_errors' => false]);

        $options = [
            'channel' => $channel,
            'payload' => $content,
        ];

        $url = "https://recon-bot.7rqtwti-zm2ubuph7uwzs.apps.gnf.lt/api/webhook";
        $request = $client->post($url, ['body' => json_encode($options) ]);

    }

}








