<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\KnownStructures;
use Vanguard\ActivityTracker;
use Vanguard\User;
use Vanguard\NewMoons;
use Auth;
use DB;


class MergeStructureController extends Controller
{

    # [14:24:53] scopehone > <url=showinfo:35835//1029852136388>1DQ1-A - Keepstar (Mindstar Technology)</url> 

    public function view_structure($structure_id) {

        # Get current structure information with structure_id.
        $structure = KnownStructures::where('str_structure_id_md5', $structure_id)
        ->first();

        # Search the database for all structures with a duplicate structure id.
        $duplicate_structures = KnownStructures::where('str_structure_id', $structure->str_structure_id)
        ->where('str_structure_id_md5', '!=', $structure_id)
        ->get();

        if($structure->str_moon) {
            $moon_data = NewMoons::where('moon_name', $structure->str_moon)->first();
        } else {
            $moon_data = "";
        }

        return view('merge_structures.view', compact('structure', 'duplicate_structures', 'moon_data'));

    }

    public function merge_structure($old_structure_id_md5, $new_structure_id_md5) {

        # Get the new structure information
        $new = KnownStructures::where('str_structure_id_md5', $new_structure_id_md5)
        ->first();

        # Get the old structure information
        $old = KnownStructures::where('str_structure_id_md5', $old_structure_id_md5)
        ->first();

        if($old->str_structure_id != $new->str_structure_id) {
            return redirect()->back()
            ->withErrors("Stop being a dick.");
        }

        # Get any other structures that match the structure id.

        $others = KnownStructures::where('str_structure_id', $old->str_structure_id)
        ->where('str_structure_id_md5', '!=', $new_structure_id_md5)
        ->where('str_structure_id_md5', '!=', $old_structure_id_md5)
        ->get();

        # We only want to merge the new structure with one previous old record and we should remove the others, if any.
        # Remove them.
        if($others) {

            foreach($others as $other) {
                $other->delete();
            }
        }

        # Migrate Activity Log from Structure to new Hash.
        $this->migrateActivityLogToDestroyedStructure($old->str_structure_id_md5, $new->str_structure_id_md5);

        # Migrate the old data to the new structure.
        # Update the hash and change the name, migrate the owner, even if it hasn't changed.


        $old->str_structure_id_md5 = $new->str_structure_id_md5;
        $old->str_name = $new->str_name;
        $old->str_standings = $new->str_standings;
        $old->str_owner_corporation_name = $new->str_owner_corporation_name;
        $old->str_owner_corporation_id = $new->str_owner_corporation_id;
        $old->str_owner_alliance_id = $new->str_owner_alliance_id;
        $old->str_owner_alliance_name = $new->str_owner_alliance_name;
        $old->str_owner_alliance_ticker = $new->str_owner_alliance_ticker;
        $old->str_system_id = $new->str_system_id;
        $old->str_system = $new->str_system;
        $old->str_constellation_id = $new->str_constellation_id;
        $old->str_constellation_name = $new->str_constellation_name;
        $old->str_region_id = $new->str_region_id;
        $old->str_region_name = $new->str_region_name;
        $old->str_destroyed = 0;

        # Merge Fit

        $old->str_fitting = $new->str_fitting;
        $old->str_value = $new->str_value;
        $old->str_state = $new->str_state;
        $old->str_status = $new->str_status;
        $old->str_has_no_fitting = $new->str_has_no_fitting;
        $old->str_market = $new->str_market;
        $old->str_capital_shipyard = $new->str_capital_shipyard;
        $old->str_hyasyoda = $new->str_hyasyoda;
        $old->str_invention = $new->str_invention;
        $old->str_manufacturing = $new->str_manufacturing;
        $old->str_research = $new->str_research;
        $old->str_supercapital_shipyard = $new->str_supercapital_shipyard;
        $old->str_biochemical = $new->str_biochemical;
        $old->str_hybrid = $new->str_hybrid;
        $old->str_moon_drilling = $new->str_moon_drilling;
        $old->str_reprocessing = $new->str_reprocessing;
        $old->str_point_defense = $new->str_point_defense;
        $old->str_dooms_day = $new->str_dooms_day;
        $old->str_guide_bombs = $new->str_guide_bombs;
        $old->str_anti_cap = $new->str_anti_cap;
        $old->str_anti_subcap = $new->str_anti_subcap;
        $old->str_t2_rigged = $new->str_t2_rigged;
        $old->str_cloning = $new->str_cloning;
        $old->str_composite = $new->str_composite;
        $old->str_package_delivered = $new->str_package_delivered;
        $old->str_hitlist = $new->str_hitlist;
        $old->str_standings = $new->str_standings;
        $old->str_vul_hour = $new->str_vul_hour;
        $old->str_vul_day = $new->str_vul_day;

        $old->save();

        ## Updated the record. We delete the 'new' record.

        # Delete the 'new' record.

        $new->delete();

        # Add an action to the activity log.

        $user_id = Auth::id();
        $action = "Merged Structure Records";
        $this->addActivityLogToStructure($user_id, $old, $action);

        return redirect()->back()
        ->withSuccess($old->str_name . " Records Merged");

    }

        public function merge_structure_with_fit($old_structure_id_md5, $new_structure_id_md5) {

        # Get the new structure information
        $new = KnownStructures::where('str_structure_id_md5', $new_structure_id_md5)
        ->first();

        # Get the old structure information
        $old = KnownStructures::where('str_structure_id_md5', $old_structure_id_md5)
        ->first();

        if($old->str_structure_id != $new->str_structure_id) {
            return redirect()->back()
            ->withErrors("Stop being a dick.");
        }

        # Get any other structures that match the structure id.

        $others = KnownStructures::where('str_structure_id', $old->str_structure_id)
        ->where('str_structure_id_md5', '!=', $new_structure_id_md5)
        ->where('str_structure_id_md5', '!=', $old_structure_id_md5)
        ->get();

        # We only want to merge the new structure with one previous old record and we should remove the others, if any.
        # Remove them.
        if($others) {

            foreach($others as $other) {
                $other->delete();
            }
        }

        # Migrate Activity Log from Structure to new Hash.
        $this->migrateActivityLogToDestroyedStructure($old->str_structure_id_md5, $new->str_structure_id_md5);

        # Migrate the old data to the new structure.
        # Update the hash and change the name, migrate the owner, even if it hasn't changed.


        $old->str_structure_id_md5 = $new->str_structure_id_md5;
        $old->str_name = $new->str_name;
        $old->str_standings = $new->str_standings;
        $old->str_owner_corporation_name = $new->str_owner_corporation_name;
        $old->str_owner_corporation_id = $new->str_owner_corporation_id;
        $old->str_owner_alliance_id = $new->str_owner_alliance_id;
        $old->str_owner_alliance_name = $new->str_owner_alliance_name;
        $old->str_owner_alliance_ticker = $new->str_owner_alliance_ticker;
        $old->str_system_id = $new->str_system_id;
        $old->str_system = $new->str_system;
        $old->str_constellation_id = $new->str_constellation_id;
        $old->str_constellation_name = $new->str_constellation_name;
        $old->str_region_id = $new->str_region_id;
        $old->str_region_name = $new->str_region_name;
        $old->str_destroyed = 0;

        $old->save();

        ## Updated the record. We delete the 'new' record.

        # Delete the 'new' record.

        $new->delete();

        # Add an action to the activity log.

        $user_id = Auth::id();
        $action = "Merged Structure Records";
        $this->addActivityLogToStructure($user_id, $old, $action);

        return redirect()->back()
        ->withSuccess($old->str_name . " Records Merged");

    }

    public function duplicate_structures() {

        # Display a table of duplicate structures for merging.

        $structures = KnownStructures::where('str_destroyed', 0)->where('str_structure_id', '>', 1)->orderBy('created_at', 'DESC')->get();
        $structuresUnique = $structures->unique('str_structure_id');
        $duplicate_structures = $structures->diff($structuresUnique);

        return view('merge_structures.duplicates', compact('duplicate_structures'));


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

    public function addActivityLogToStructure($user_id, $structure, $user_action) {

        $user = User::where('id', $user_id)
        ->first();

        $action = new ActivityTracker;
        $action->at_user_id = $user->id;
        $action->at_username = $user->username;
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
}
