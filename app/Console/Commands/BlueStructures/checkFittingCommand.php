<?php

namespace Vanguard\Console\Commands\BlueStructures;

use Illuminate\Console\Command;

use Vanguard\KnownStructures;

class checkFittingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'structure:blue:check:fitting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Blue Structures to see if they are fit correct.';

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
        $structures = KnownStructures::where('str_standings', '>', 0.1) # Get Blues Only
        ->where('str_region_name', '=', "Delve") # Get Only Delve
        ->where('str_owner_corporation_id', '>', 2) # I Only care if it has a corporation id.
        ->where('str_size', '!=', "FLEX") # I don't want fucking cynos or gates
        ->where('str_destroyed', 0) # It has to be alive, duh!
        ->where(function($query) {
            $query->where('str_anti_cap', 1)
            ->orWhere('str_anti_subcap', 0);
        })
        ->where(function($query) {
            $query->where('str_anti_cap', 0)
            ->orWhere('str_anti_subcap', 1);
        })
        ->orderBy('str_name', 'ASC')
        ->get();




        $this->info('Found ' . count($structures) . ' Structures.');
        
        foreach($structures as $structure) {
            $this->info($structure->str_name . ' @ ' . $structure->str_owner_corporation_name . ' @ ' . $structure->str_type . ' @ https://recon.gnf.lt/structures/' . $structure->str_structure_id_md5 . '/view' );
        }
        

    }
}
