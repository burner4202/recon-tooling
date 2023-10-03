<?php

namespace Vanguard\Console\Commands\Map;

use Illuminate\Console\Command;

use Vanguard\SolarSystems;

class GenerateEVEMapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eve:GenerateEVEMap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a map, duh!';

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
        $systems = SolarSystems::where('ss_security_class', '!=', "")->get();

        # init arrays

        $nodes = array();
        $links = array();

        # Build Arrays

        foreach ($systems as $system) {

            $coordinates = json_decode($system->ss_position);

            $nodes[] = [
                'system_name'               => $system->ss_system_name,
                'constellation_name'     => $system->ss_constellation_name,
                //'ss_region_name'            => $system->ss_region_name,
                //'security_status'           => $system->ss_security_status,
                //'x'                         => $coordinates->x,
                //'y'                         => $coordinates->y,
                //'z'                         => $coordinates->z,
            ];

        }

        $json = json_encode($nodes);
        dd($json);

    }


}
