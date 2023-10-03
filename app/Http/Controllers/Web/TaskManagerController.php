<?php

/*
 * Goonswarm Federation Recon Tools
 *
 * Developed by scopehone <scopeh@gmail.com>
 * In conjuction with Natalya Spaghet & Mindstar Technology 
 *
 */

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Datetime;

use Auth;
use Log;
use DB;
use Input;
use Carbon\Carbon;
use Vanguard\User;
use Vanguard\SolarSystems;
use Vanguard\Regions;
use Vanguard\Constellations;
use Vanguard\TypeIDs;
use Vanguard\NPCKills;
use Vanguard\KnownStructures;
use Vanguard\Corporations;
use Vanguard\UpwellModules;
use Vanguard\ActivityTracker;
use Vanguard\TaskManager;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

class TaskManagerController extends Controller
{


	public function overview() {

		$now = Carbon::now()->toDateTimeString();
		$tasks = [
			'Scan the entire system and update everything!' => 'Scan the entire system and update everything!',
			'Put cyno in place.' => 'Put cyno in place.',
			//'Scan for New Structures' => 'Scan for New Structures',
			//'Update Structures State' => 'Update Structures State',
			//'Update Structures Meta Data' => 'Update Structures Meta Data',
			//'Update Structures Fittings' => 'Update Structures Fittings',
			//'Update Vulnerability Timers' => 'Update Vulnerability Timers',
			//'Check for Cyno Jammers' => 'Check for Cyno Jammers',
			//'Check for Jump Gates' => 'Check for Jump Gates',
			//'Get Cyno in Position' => 'Get Cyno in Position',
		];

		$prority = [
			'Low Prority' => 'Low Prority',
			'High Prority' => 'High Prority',
			'Right Now!' => 'Right Now!',
		];

		$pending_tasks = TaskManager::where('tm_state', 0)
		->orderBy('tm_created_datetime_at', 'DESC')
		->paginate(500, ['*'], 'pending');

		$outstanding_tasks = TaskManager::where('tm_state', 1)
		->orderBy('tm_created_datetime_at', 'ASC')
		->paginate(500, ['*'], 'outstanding');

		$inprogress_tasks = TaskManager::where('tm_state', 2)
		->orderBy('tm_accepted_datetime_at', 'ASC')
		->paginate(500, ['*'], 'inprogress');

		$completed_tasks = TaskManager::where('tm_state', 3)
		->orderBy('tm_completed_datetime_at', 'DESC')
		->paginate(500, ['*'], 'completed');

		return view('taskmanager.overview', compact('tasks', 'prority', 'pending_tasks', 'outstanding_tasks', 'inprogress_tasks', 'completed_tasks', 'now'));
		
	}

	public function add_task_to_pending(Request $request) {

		$user = Auth::user();
		$system = $request->input('system');
		$constellation = $request->input('constellation');
		$region = $request->input('region');
		$tasks = $request->input('tasks');
		$prority = $request->input('prority');
		$notes = $request->input('notes');
		$now = Carbon::now()->toDateTimeString();


		if($system) { 
			$system_properties = SolarSystems::where('ss_system_name', $system)
			->first(); 



			// Add one System task for Pending

			$task = new TaskManager;
			$task->tm_created_by_user_id 			= $user->id;
			$task->tm_created_by_user_username 		= $user->username;
			$task->tm_solar_system_id 				= $system_properties->ss_system_id;
			$task->tm_solar_system_name				= $system_properties->ss_system_name;
			$task->tm_constellation_id				= $system_properties->ss_constellation_id;
			$task->tm_constellation_name			= $system_properties->ss_constellation_name;
			$task->tm_region_id						= $system_properties->ss_region_id;
			$task->tm_region_name					= $system_properties->ss_region_name;
			$task->tm_task							= $tasks;
			$task->tm_prority						= $prority;
			$task->tm_notes							= $notes;
			$task->tm_created_datetime_at			= $now;
			$task->tm_state							= 0; 
			$task->save();

			return redirect()
			->back()
			->withSuccess('Added ' . $system . ' system to tasks for pending and review/acceptance.');

		}

		if($constellation) { 
			$constellation_properties = SolarSystems::where('ss_constellation_name', $constellation)
			->get(); 

			// Add all systems for constellation

			foreach($constellation_properties as $system) {

				// Add one System task for Pending

				$task = new TaskManager;
				$task->tm_created_by_user_id 			= $user->id;
				$task->tm_created_by_user_username 		= $user->username;
				$task->tm_solar_system_id 				= $system->ss_system_id;
				$task->tm_solar_system_name				= $system->ss_system_name;
				$task->tm_constellation_id				= $system->ss_constellation_id;
				$task->tm_constellation_name			= $system->ss_constellation_name;
				$task->tm_region_id						= $system->ss_region_id;
				$task->tm_region_name					= $system->ss_region_name;
				$task->tm_task							= $tasks;
				$task->tm_prority						= $prority;
				$task->tm_notes							= $notes;
				$task->tm_created_datetime_at			= $now;
				$task->tm_state							= 0; 
				$task->save();


			}

			return redirect()
			->back()
			->withSuccess('Added ' . count($constellation_properties) . ' systems to tasks for pending and review/acceptance.');

		}


		if($region) { 
			$region_properties = SolarSystems::where('ss_region_name', $region)
			->get(); 	

			// Add all systems for constellation

			foreach($region_properties as $system) {

				// Add one System task for Pending

				$task = new TaskManager;
				$task->tm_created_by_user_id 			= $user->id;
				$task->tm_created_by_user_username 		= $user->username;
				$task->tm_solar_system_id 				= $system->ss_system_id;
				$task->tm_solar_system_name				= $system->ss_system_name;
				$task->tm_constellation_id				= $system->ss_constellation_id;
				$task->tm_constellation_name			= $system->ss_constellation_name;
				$task->tm_region_id						= $system->ss_region_id;
				$task->tm_region_name					= $system->ss_region_name;
				$task->tm_task							= $tasks;
				$task->tm_prority						= $prority;
				$task->tm_notes							= $notes;
				$task->tm_created_datetime_at			= $now;
				$task->tm_state							= 0; 
				$task->save();


			}

			return redirect()
			->back()
			->withSuccess('Added ' . count($region_properties) . ' systems to tasks for pending and review/acceptance.');

		}
	}

	public function dispatch_from_system($system_id) {

		$user = Auth::user();
		$now = Carbon::now()->toDateTimeString();

		$system = SolarSystems::where('ss_system_id', $system_id)
		->first(); 

		$task = new TaskManager;
		$task->tm_created_by_user_id 			= $user->id;
		$task->tm_created_by_user_username 		= $user->username;
		$task->tm_solar_system_id 				= $system->ss_system_id;
		$task->tm_solar_system_name				= $system->ss_system_name;
		$task->tm_constellation_id				= $system->ss_constellation_id;
		$task->tm_constellation_name			= $system->ss_constellation_name;
		$task->tm_region_id						= $system->ss_region_id;
		$task->tm_region_name					= $system->ss_region_name;
		$task->tm_task							= "Scan the entire system and update everything!";
		$task->tm_prority						= "High Prority";
		$task->tm_notes							= "";
		$task->tm_created_datetime_at			= $now;
		$task->tm_state							= 1; 
		$task->save();

		return redirect()
		->back()
		->withSuccess('Task: ' . $task->id . ' dispatched to outstanding queue and ready to be claimed.');
	}

	public function outstanding() {

		$now = Carbon::now()->toDateTimeString();
		$outstanding_tasks = TaskManager::where('tm_state', 1)
		->orderBy('tm_created_datetime_at', 'ASC')
		->paginate(500, ['*'], 'outstanding');
		
		return view('taskmanager.outstanding', compact('outstanding_tasks', 'now'));
		
	}

	public function inprogress() {

		// Show Taks Inprogress for User Account Only.
		$user = Auth::user();
		$now = Carbon::now()->toDateTimeString();
		$inprogress_tasks = TaskManager::where('tm_state', 2)
		->where('tm_accepted_by_user_id', $user->id)
		->orderBy('tm_created_datetime_at', 'ASC')
		->paginate(500, ['*'], 'inprogress');

		return view('taskmanager.inprogress', compact('inprogress_tasks', 'now'));

	}

	public function dispatch($id) {

		$task = TaskManager::where('id', $id)->first();

		$task->tm_state = 1;
		$task->save();

		return redirect()
		->back()
		->withSuccess('Task: ' . $task->id . ' dispatched to outstanding queue and ready to be claimed.');
	}

	public function remove_from_dispatch($id) {

		$remove_task = TaskManager::where('id', $id)->first();
		$remove_task->destroy($id);

		return redirect()
		->back()
		->withSuccess('Removed ' . $remove_task->id . ' task from the pending queue.');
	}


	public function claim($id) {

		$user = Auth::user();
		$now = Carbon::now()->toDateTimeString();

		$task = TaskManager::where('id', $id)->first();

		if($task->tm_state == 2) {
			return redirect()
			->back()
			->withErrors('Stop being a twat.');
		}

		$task->tm_state = 2;
		$task->tm_accepted_by_user_id = $user->id;
		$task->tm_accepted_by_user_username = $user->username;
		$task->tm_accepted_datetime_at = $now;
		$task->save();

		return redirect()
		->back()
		->withSuccess('Task: ' . $task->id . ' claimed, please complete as soon as possible.');
	}

	public function unclaim($id) {

		$user = Auth::user();
		$now = Carbon::now()->toDateTimeString();

		$task = TaskManager::where('id', $id)->first();

		if($task->tm_accepted_by_user_id != $user->id) {
			return redirect()
			->back()
			->withErrors('Stop being a twat.');
		}
		$task->tm_state = 1;
		$task->tm_accepted_by_user_id = null;
		$task->tm_accepted_by_user_username = null;
		$task->tm_accepted_datetime_at = null;
		$task->save();

		return redirect()
		->back()
		->withSuccess('Task: ' . $task->id . ' unclaimed, slacker.');
	}



	public function complete($id) {

		$user = Auth::user();
		$now = Carbon::now()->toDateTimeString();

		$task = TaskManager::where('id', $id)->first();

		// Check it belongs to the user.

		if($task->tm_accepted_by_user_id != $user->id) {
			return redirect()
			->back()
			->withErrors("Stop being a dickhead, or i'll ban your shit.");
		}

		$task->tm_state = 3;
		$task->tm_completed_datetime_at = $now;
		$task->save();

		return redirect()
		->back()
		->withSuccess('Task: ' . $task->id . ' completed, thanks for all the fish.');
	}

	public function return_to_outstanding($id) {

		$task = TaskManager::where('id', $id)->first();

		$task->tm_state = 1;
		$task->tm_accepted_by_user_id = "";
		$task->tm_accepted_by_user_username = "";
		$task->tm_accepted_datetime_at = "";
		$task->save();

		return redirect()
		->back()
		->withSuccess('Task: ' . $task->id . ' has been put back into the outstanding queue for reallocation.');
	}

	public function dispatch_all() {

		$task = TaskManager::where('tm_state', 0)->get();

		$amount = $task->count();

		foreach ($task as $distpatch) {

			$add_task = TaskManager::where('id', $distpatch->id)->first();
			$add_task->tm_state = 1;
			$add_task->save();
		}

		return redirect()
		->back()
		->withSuccess('Added ' . $amount . ' tasks to the outstanding queue.');

	}

	public function remove_all() {

		$task = TaskManager::where('tm_state', 0)->get();

		$amount = $task->count();

		foreach ($task as $delete) {

			$remove_task = TaskManager::where('id', $delete->id)->first();
			$remove_task->destroy($delete->id);
		}

		return redirect()
		->back()
		->withSuccess('Removed ' . $amount . ' tasks from the pending queue.');

	}





		/*
				$table->increments('id');
     		$table->integer('tm_created_by_user_id');
     		$table->text('tm_created_by_user_username');
     		$table->integer('tm_solar_system_id');
     		$table->text('tm_solar_system_name');
     		$table->integer('tm_constellation_id');
     		$table->text('tm_constellation_name');
     		$table->integer('tm_region_id');
     		$table->text('tm_region_name');
     		$table->text('tm_task');
     		$table->text('tm_prority');
     		$table->text('tm_notes');
     		$table->datetime('tm_created_datetime_at');
     		
     		 * 0 = Pending
     		 * 1 = Dispatched, Sat Outstanding
     		 * 2 = Claimed, Not Completed
     		 * 3 = Completed
     		
     		$table->text('tm_state');
     		$table->integer('tm_accepted_by_user_id');
     		$table->text('tm_accepted_by_user_username');
     		$table->datetime('tm_accepted_datetime_at');
     		$table->timestamps();
     	*/

     	// Add to Pending
     	}
