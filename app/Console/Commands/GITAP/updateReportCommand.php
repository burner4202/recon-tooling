<?php

/*

Generator Alliance Reports Based on the following;

Alliance Health (Gauge the Health of an Alliance based on the factors below.) - Weekly Report

The intention of this would be a weekly report for every alliance, based on conversations with Atrum, known as Goonswarm Intel Threat Analysis Programme (GITAP)

Snapshot the data sets we currently have within the recon tools weekly, in turn, use this aggregated data set over time to identify changes in behaviour of a target alliance.

# Sov

Number of IHUBS
Number of TCUS
Average ADM

Number of IHUBS per Region
Number of TCUs per Region
Average ADM per Region

# NPC Kills

NPC Kills per Region where SOV is owned
Identify top NPC Ratting systems

# Structures

Number of Structures
Number of Structures per Type per Region
Total Value of Structures
Total Value of Structures per Region

# Indexes

Number of active systems where sov is owned for each type of index

Identify Top 5 Systems

Manufacturing 
Research Time Efficiency    
Research Material Efficiency    
Copying     
Invention   
Reactions   

Average Index per type per region where sov is owned

Manufacturing 
Research Time Efficiency    
Research Material Efficiency    
Copying     
Invention   
Reactions 

Identify systems with a index less than 0.5%, typically this can be known as a unused system, or dead system.
Could be used along with average ADM as a function to show an alliance is overstretched.   

Identify systems within the reporting period, of significantly high delta's in each index type.

i.e; (9:01:27 PM) ReconBot: O-97ZG (-0.06) in Paragon Soul has had a 2,080.00% increase in manufacturing activity. Index currently at: 2.18% https://recon.gnf.lt/system/30004692 

# Moons

Based on structures tagged with a moon drill that is not dead, identify how many moon drills are active on an R32/R64 where we have moon data.

# Capital Pilots

Snapshot existing capital tracking dataset to capture in time known capital pilots.

Titan Pilots    
Faction Titan Pilots    
Supercarrier Pilots
Faction Supercarrier Pilots
Carrier Pilots
Force Auxiliary Pilots
Dreadnought Pilots
Faction Dreadnought Pilots

*/

namespace Vanguard\Console\Commands\GITAP;

use Illuminate\Console\Command;

use Carbon\Carbon;

use Vanguard\Alliances;
use Vanguard\KnownStructures;
use Vanguard\Corporations;
use Vanguard\NewMoons;
use Vanguard\NPCKills;
use Vanguard\SovStructures;
use Vanguard\SystemCostIndices;
use Vanguard\GITAP; # Model to hold weekly reports.


class updateReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gitap:updateReports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the GITAP Reports';

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
        # Week Number/Year

        $now = Carbon::now();

        # $now->weekOfYear . "-" . $now->year

        # Lets just do one alliance to see how it turns out; TEST - 498125261
        # Sov
        $this->info('Weekly Data on TEST Alliance');

        $total_ihubs = SovStructures::where('alliance_id', '498125261')
        ->where('structure_type_name', '=', 'Infrastructure Hub')
        ->count();

        $total_tcus = SovStructures::where('alliance_id', '498125261')
        ->where('structure_type_name', '=', 'Territorial Claim Unit')
        ->count();

        $this->info('IHUBS: ' . $total_ihubs);

        # IHUB Per Region
        $regions_ihubs = SovStructures::where('alliance_id', '498125261')
        ->where('structure_type_name', '=', 'Infrastructure Hub')
        ->groupBy('region_id')
        ->get();

        
        foreach($regions_ihubs as $region) {

         $ihub_per_region = $this->alliance_ihubs_per_region('498125261', $region->region_id);
         $average_adm_ihub = $this->alliance_average_adm_ihub_per_region('498125261', $region->region_id);

         $this->info('IHUB Region ' . $region->region_name . ' Count ' . $ihub_per_region . ' - Average ADM: ' . $average_adm_ihub);

     }


     $this->info('TCUs: ' . $total_tcus);

        # TCUs Per Region
     $regions_tcus = SovStructures::where('alliance_id', '498125261')
     ->where('structure_type_name', '=', 'Territorial Claim Unit')
     ->groupBy('region_id')
     ->get();


     foreach($regions_tcus as $region) {

        $tcu_per_region = $this->alliance_tcus_per_region('498125261', $region->region_id);
        $average_adm_tcu = $this->alliance_average_adm_tcu_per_region('498125261', $region->region_id);

        $this->info('TCU Region ' . $region->region_name . ' Count ' . $tcu_per_region . ' - Average ADM: ' . $average_adm_tcu);

    }


    ## Number of Structures


    $structures = KnownStructures::where('str_owner_alliance_id', 498125261)
    ->where('str_destroyed', 0)
    ->count();

    $this->info('Total Live Structures: ' . $structures);

    $structure_regions = KnownStructures::where('str_owner_alliance_id', 498125261)
    ->where('str_destroyed', 0)
    ->groupBy('str_region_id')
    ->get();

    $this->info('Structures per Region');

    foreach($structure_regions as $region) {

        if($region->str_region_id > 1) {

         $count = $this->alliance_structure_count_per_region('498125261', $region->str_region_id);

         $this->info('Structures per Region ' . $region->str_region_name . ' Structure Count ' . $count);
     }
 }









}

public function alliance_tcus_per_region($alliance_id, $region_id) {
    $data = SovStructures::where('alliance_id', $alliance_id)
    ->where('structure_type_name', '=', 'Territorial Claim Unit')
    ->where('region_id', $region_id)
    ->count();

    return $data;
}

public function alliance_ihubs_per_region($alliance_id, $region_id) {
    $data = SovStructures::where('alliance_id', $alliance_id)
    ->where('structure_type_name', '=', 'Infrastructure Hub')
    ->where('region_id', $region_id)
    ->count();

    return $data;
}

public function alliance_average_adm_ihub_per_region($alliance_id, $region_id) {
    $data = SovStructures::where('alliance_id', $alliance_id)
    ->where('structure_type_name', '=', 'Infrastructure Hub')
    ->where('region_id', $region_id)
    ->avg('vulnerability_occupancy_level');

    return $data;
}

public function alliance_average_adm_tcu_per_region($alliance_id, $region_id) {
    $data = SovStructures::where('alliance_id', $alliance_id)
    ->where('structure_type_name', '=', 'Territorial Claim Unit')
    ->where('region_id', $region_id)
    ->avg('vulnerability_occupancy_level');

    return $data;
}

public function alliance_structure_count_per_region($alliance_id, $region_id) {

   $data = KnownStructures::where('str_owner_alliance_id', $alliance_id)
   ->where('str_destroyed', 0)
   ->where('str_region_id',  $region_id)
   ->count();

   return $data;
}


}
