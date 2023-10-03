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

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;


class ActivityTrackerController extends Controller
{
	public function index() {

		$search = Input::input('search');
		$input_username = Input::input('username');
		$input_action = Input::input('action');
		$input_system = Input::input('system');

		$usernames = ActivityTracker::sortable()
		->groupBy('at_username')
		->get();

		$username = 		['' => 'All'];
		$action = 		[
			'' => 'All',
			'Stored Structure Fitting' => 'Stored Structure Fitting',
			'Stored Structure Meta Data' => 'Stored Structure Meta Data',
			'Structure added to the Hitlist.' => 'Structure added to the Hitlist.',
			'Structure Destroyed' => 'Structure Destroyed',
			'Structure has a Fit' => 'Structure has a Fit',
			'Structure has No Fitting' => 'Structure has No Fitting',
			'Structure is reinforced to Armor' => 'Structure is reinforced to Armor',
			'Structure is reinforced to Hull' => 'Structure is reinforced to Hull',
			'Structure set to Anchoring' => 'Structure set to Anchoring',
			'Structure set to High Power' => 'Structure set to High Power',
			'Structure set to Low Power' => 'Structure set to Low Power',
			'Structure set to Reinforced' => 'Structure set to Reinforced',
			'Structure set to Unanchoring' => 'Structure set to Unanchoring',
			'Structure Status Cleared.' => 'Structure Status Cleared.',
			'Package Delivered' => 'Package Delivered',
			'Package Removed' => 'Package Removed',


		];

		foreach($usernames as $each_username) { 
			// We also have a username, so we don't need an if statement as below.
			$username[$each_username['at_username']] = $each_username['at_username']; 
		}

		$query = ActivityTracker::query();

		if ($input_username) {
			$query->where('at_username', $input_username);
		}

		if ($input_system) {
			$query->where('at_system_name', $input_system);
		}

		if ($input_action) {
			$query->where('at_action', $input_action);
		}

		if ($search) {
			$query->where(function ($q) use ($search) {
				$q->sortable();
				$q->where('at_system_name', "like", "%{$search}%");
				$q->orWhere('at_corporation_name', 'like', "%{$search}%");
				$q->orWhere('at_structure_name', 'like', "%{$search}%");
				$q->orWhere('at_action', 'like', "%{$search}%");
			});
		}

		$activity = $query
		->sortable()
		->orderBy('created_at', 'DESC')
		->paginate(100);

		if ($search) {
			$activity->appends(['search' => $search]);
		}




		return view('activitytracker.index', compact('activity', 'username', 'action'));
	}

	public function activity_metrics_index() {

		$months = ActivityTracker::selectRaw(
			'
			YEAR(created_at) year, 
			MONTHNAME(created_at) month, 
			COUNT(*) activity, 
			COUNT( (CASE WHEN at_action = "Package Delivered" THEN at_action END) ) AS packages_delivered,
			COUNT( (CASE WHEN at_action = "Package Removed" THEN at_action END) ) AS packages_removed,
			COUNT( (CASE WHEN at_action LIKE "Structure Belongs%" THEN at_action END) ) AS structure_meta_data_added,
			COUNT( (CASE WHEN at_action = "Structure Destroyed" THEN at_action END) ) AS structure_destroyed,
			COUNT( (CASE WHEN at_action = "Structure has a Fit" THEN at_action END) ) AS structure_has_fit,
			COUNT( (CASE WHEN at_action = "Stored Structure Fitting" THEN at_action END) ) AS structure_fitting_stored,
			COUNT( (CASE WHEN at_action = "Structure has No Fitting" THEN at_action END) ) AS structure_has_no_ftting,
			COUNT( (CASE WHEN at_action = "Structure is reinforced to Armor" THEN at_action END) ) AS structure_reinforced_armor,
			COUNT( (CASE WHEN at_action = "Structure is reinforced to Hull" THEN at_action END) ) AS structure_reinforced_hull,
			COUNT( (CASE WHEN at_action = "Structure set to Anchoring" THEN at_action END) ) AS structure_anchoring,
			COUNT( (CASE WHEN at_action = "Structure set to High Power" THEN at_action END) ) AS structure_high_power,
			COUNT( (CASE WHEN at_action = "Structure set to Low Power" THEN at_action END) ) AS structure_low_power,
			COUNT( (CASE WHEN at_action = "Structure set to Reinforced" THEN at_action END) ) AS structure_reinforced,
			COUNT( (CASE WHEN at_action = "Structure set to Unanchoring" THEN at_action END) ) AS structure_unanchoring,
			COUNT( (CASE WHEN at_action = "Structure Status Cleared." THEN at_action END) ) AS structure_status_clear
			')
		->groupBy('year', 'month')
		->orderBy('created_at', 'desc')
		->get();

		$chart_months = $months->reverse();
		# Init Chart Array
		$chart = array();

		# Make Stacked Chart Array from Data for ChartJS.
		foreach($chart_months as $month) {

			$chart[$month->month . "-" . $month->year] = [
				'structure_meta_data_added' => $month->structure_meta_data_added,
				'structure_destroyed' => $month->structure_destroyed,
				'structure_has_fit' => $month->structure_has_fit,
				'structure_fitting_stored' => $month->structure_fitting_stored,
				'structure_has_no_ftting' => $month->structure_has_no_ftting,
				'structure_reinforced_armor' => $month->structure_reinforced_armor,
				'structure_reinforced_hull' => $month->structure_reinforced_hull,
				'structure_anchoring' => $month->structure_anchoring,
				'structure_high_power' => $month->structure_high_power,
				'structure_low_power' => $month->structure_low_power,
				'structure_reinforced' => $month->structure_reinforced,
				'structure_unanchoring' => $month->structure_unanchoring,
				'structure_status_clear' => $month->structure_status_clear,
				'packages_delivered' => $month->packages_delivered,
				'packages_removed' => $month->packages_removed,
			];

		}

		return view('activitytracker.metrics_index', compact('months', 'chart'));
	}

	public function month_year_view($month_year) {

		list($month, $year) = explode("-", $month_year);

		$month_number = Carbon::parse('1'. $month)->month;

		# Run Query to get the goodies.
		$results = ActivityTracker::whereMonth('created_at', '=', $month_number)
		->whereYear('created_at', '=', $year)
		->selectRaw(
			'
			YEAR(created_at) year, 
			MONTHNAME(created_at) month, 
			at_username as at_username,
			COUNT(*) activity, 
			COUNT( (CASE WHEN at_action = "Package Delivered" THEN at_action END) ) AS packages_delivered,
			COUNT( (CASE WHEN at_action = "Package Removed" THEN at_action END) ) AS packages_removed,
			COUNT( (CASE WHEN at_action LIKE "Structure Belongs%" THEN at_action END) ) AS structure_meta_data_added,
			COUNT( (CASE WHEN at_action = "Structure Destroyed" THEN at_action END) ) AS structure_destroyed,
			COUNT( (CASE WHEN at_action = "Structure has a Fit" THEN at_action END) ) AS structure_has_fit,
			COUNT( (CASE WHEN at_action = "Stored Structure Fitting" THEN at_action END) ) AS structure_fitting_stored,
			COUNT( (CASE WHEN at_action = "Structure has No Fitting" THEN at_action END) ) AS structure_has_no_ftting,
			COUNT( (CASE WHEN at_action = "Structure is reinforced to Armor" THEN at_action END) ) AS structure_reinforced_armor,
			COUNT( (CASE WHEN at_action = "Structure is reinforced to Hull" THEN at_action END) ) AS structure_reinforced_hull,
			COUNT( (CASE WHEN at_action = "Structure set to Anchoring" THEN at_action END) ) AS structure_anchoring,
			COUNT( (CASE WHEN at_action = "Structure set to High Power" THEN at_action END) ) AS structure_high_power,
			COUNT( (CASE WHEN at_action = "Structure set to Low Power" THEN at_action END) ) AS structure_low_power,
			COUNT( (CASE WHEN at_action = "Structure set to Reinforced" THEN at_action END) ) AS structure_reinforced,
			COUNT( (CASE WHEN at_action = "Structure set to Unanchoring" THEN at_action END) ) AS structure_unanchoring,
			COUNT( (CASE WHEN at_action = "Structure Status Cleared." THEN at_action END) ) AS structure_status_clear
			')
		->groupBy('at_username')
		->orderBy('at_username', 'asc')
		->get();

		# Init Chart Array
		$chart = array();

		# Make Stacked Chart Array from Data for ChartJS.
		foreach($results as $account) {

			$chart[$account->at_username] = [
				'structure_meta_data_added' => $account->structure_meta_data_added,
				'structure_destroyed' => $account->structure_destroyed,
				'structure_has_fit' => $account->structure_has_fit,
				'structure_fitting_stored' => $account->structure_fitting_stored,
				'structure_has_no_ftting' => $account->structure_has_no_ftting,
				'structure_reinforced_armor' => $account->structure_reinforced_armor,
				'structure_reinforced_hull' => $account->structure_reinforced_hull,
				'structure_anchoring' => $account->structure_anchoring,
				'structure_high_power' => $account->structure_high_power,
				'structure_low_power' => $account->structure_low_power,
				'structure_reinforced' => $account->structure_reinforced,
				'structure_unanchoring' => $account->structure_unanchoring,
				'structure_status_clear' => $account->structure_status_clear,
				'packages_delivered' => $account->packages_delivered,
				'packages_removed' => $account->packages_removed,
			];

		}

		return view('activitytracker.metrics_monthly_index', compact('results', 'chart', 'month_year'));
	}


		public function month_year_user_view($month_year, $at_username) {
		list($month, $year) = explode("-", $month_year);

		$month_number = Carbon::parse('1'. $month)->month;
		$days_of_month = Carbon::parse('1'. $month)->daysInMonth;

		$query = [
			'Package Delivered',
			'Package Removed'
		];

		# Run Query to get the goodies.
		$results = ActivityTracker::where('at_username', $at_username)
		->whereMonth('created_at', '=', $month_number)
		->whereYear('created_at', '=', $year)
		//->whereIn('at_action', $query)
		->selectRaw(
			'
			at_username as at_username,
			COUNT(*) activity, 
			COUNT( (CASE WHEN at_action = "Package Delivered" THEN at_action END) ) AS packages_delivered,
			COUNT( (CASE WHEN at_action = "Package Removed" THEN at_action END) ) AS packages_removed,
			COUNT( (CASE WHEN at_action LIKE "Structure Belongs%" THEN at_action END) ) AS structure_meta_data_added,
			COUNT( (CASE WHEN at_action = "Structure Destroyed" THEN at_action END) ) AS structure_destroyed,
			COUNT( (CASE WHEN at_action = "Structure has a Fit" THEN at_action END) ) AS structure_has_fit,
			COUNT( (CASE WHEN at_action = "Stored Structure Fitting" THEN at_action END) ) AS structure_fitting_stored,
			COUNT( (CASE WHEN at_action = "Structure has No Fitting" THEN at_action END) ) AS structure_has_no_ftting,
			COUNT( (CASE WHEN at_action = "Structure is reinforced to Armor" THEN at_action END) ) AS structure_reinforced_armor,
			COUNT( (CASE WHEN at_action = "Structure is reinforced to Hull" THEN at_action END) ) AS structure_reinforced_hull,
			COUNT( (CASE WHEN at_action = "Structure set to Anchoring" THEN at_action END) ) AS structure_anchoring,
			COUNT( (CASE WHEN at_action = "Structure set to High Power" THEN at_action END) ) AS structure_high_power,
			COUNT( (CASE WHEN at_action = "Structure set to Low Power" THEN at_action END) ) AS structure_low_power,
			COUNT( (CASE WHEN at_action = "Structure set to Reinforced" THEN at_action END) ) AS structure_reinforced,
			COUNT( (CASE WHEN at_action = "Structure set to Unanchoring" THEN at_action END) ) AS structure_unanchoring,
			COUNT( (CASE WHEN at_action = "Structure Status Cleared." THEN at_action END) ) AS structure_status_clear,
			DAY(created_at) as day
			')
		->groupBy('day')
		->orderBy('day', 'asc')
		->get();

		# Init Chart Array
		$chart = array();

		foreach($results as $account) {
			$chart[$account->day] = [
				'structure_meta_data_added' => $account->structure_meta_data_added,
				'structure_destroyed' => $account->structure_destroyed,
				'structure_has_fit' => $account->structure_has_fit,
				'structure_fitting_stored' => $account->structure_fitting_stored,
				'structure_has_no_ftting' => $account->structure_has_no_ftting,
				'structure_reinforced_armor' => $account->structure_reinforced_armor,
				'structure_reinforced_hull' => $account->structure_reinforced_hull,
				'structure_anchoring' => $account->structure_anchoring,
				'structure_high_power' => $account->structure_high_power,
				'structure_low_power' => $account->structure_low_power,
				'structure_reinforced' => $account->structure_reinforced,
				'structure_unanchoring' => $account->structure_unanchoring,
				'structure_status_clear' => $account->structure_status_clear,
				'packages_delivered' => $account->packages_delivered,
				'packages_removed' => $account->packages_removed,
				'day' => $account->day,
			]; 
		} 


		# Make Stacked Chart Array from Data for ChartJS.

		return view('activitytracker.month_user_report', compact('results', 'chart', 'month_year', 'at_username'));

	}


}
