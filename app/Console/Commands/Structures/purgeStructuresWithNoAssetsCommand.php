<?php

namespace Vanguard\Console\Commands\Structures;

use Illuminate\Console\Command;

use Vanguard\KnownStructures;
use Vanguard\ActivityTracker;
use Carbon\Carbon;

class purgeStructuresWithNoAssetsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'structure:purge:no:assets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Destroys structures that have do not have an asset present.';

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
        # Check structure vertified date is older than 3 days.
        # Check assets daily, if the assets do not update the date when checked, they no longer exist.

        $date = Carbon::now()->subDays(3);

        $structures = KnownStructures::where('str_destroyed', 0)
        ->whereDate('str_vertified_package', '<', $date)
        ->get();

        $this->info('Found ' . count($structures));

        foreach($structures as $structure) {

            $this->info($structure->str_name . ' marked as destroyed');

            $this->destroy($structure->str_structure_id_md5);

        }

        //dd($date . ' ' . count($structures));


    }

    public function destroy($structure_id)
    {

        $structure = KnownStructures::where('str_structure_id_md5', $structure_id) 
        ->first();

        $destroy_date = Carbon::now()->format('d-m-Y-H-i-s');
        $md5 = $structure->str_structure_id_md5;
        $new_hash = $md5 . "-dead-" . $destroy_date;

        $structure->str_structure_id_md5 = $new_hash;
        $structure->str_destroyed = 1;
        $structure->save();

        $action = "Structure Assets, No Longer Found";
        $this->addActivityLogToStructure($structure, $action);

        $action = "Structure Destroyed";
        $this->addActivityLogToStructure($structure, $action);

        # Migrate Activity Log from Structure to new Hash.
        $this->migrateActivityLogToDestroyedStructure($md5, $new_hash);

    }

    public function addActivityLogToStructure($structure, $user_action) {

        $user = "System";

        $action = new ActivityTracker;
        $action->at_user_id = 0;
        $action->at_username = $user;
        $action->at_structure_id = $structure->str_structure_id;
        $action->at_structure_hash = $structure->str_structure_id_md5;
        $action->at_structure_name = $structure->str_name;
        $action->at_system_id = $structure->str_system_id;
        $action->at_system_name = $structure->str_system;
        $action->at_corporation_id = $structure->str_owner_corporation_id;
        $action->at_corporation_name = $structure->str_owner_corporation_name;
        $action->at_action = $user_action;
        $action->save();
    }


    public function migrateActivityLogToDestroyedStructure($structure_hash, $new_structure_hash) {

        # Get all previous activity.
        $activity_logs = ActivityTracker::where('at_structure_hash', $structure_hash)->get();

        # Migrate it to new hash.

        if($activity_logs) {

            foreach($activity_logs as $activity) {

                # Update Records

                ActivityTracker::where(
                    [
                        'at_structure_hash' => $activity->at_structure_hash,
                    ]
                )->update(
                    [
                        'at_structure_hash' => $new_structure_hash
                    ]

                );

            }
        }
    }
}
