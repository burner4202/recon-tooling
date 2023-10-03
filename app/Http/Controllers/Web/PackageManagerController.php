<?php

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
use Vanguard\PackageManagerPayments;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;



class PackageManagerController extends Controller
{

	public function monthly_index() {

		# Run Query to get the goodies.
		$months = ActivityTracker::selectRaw(
			'
			YEAR(created_at) year, 
			MONTHNAME(created_at) month, 
			COUNT(*) activity, 
			COUNT( (CASE WHEN at_action = "Package Delivered" THEN at_action END) ) AS packages_delivered,
			COUNT( (CASE WHEN at_action = "Package Removed" THEN at_action END) ) AS packages_removed,
			COUNT( (CASE WHEN at_action = "Stored Structure Meta Data" THEN at_action END) ) AS structure_meta_data_added,
			COUNT( (CASE WHEN at_action = "Structure Destroyed" THEN at_action END) ) AS structure_destroyed,
			COUNT( (CASE WHEN at_action = "Structure has a Fit" THEN at_action END) ) AS structure_fitted
			')
		->groupBy('year', 'month')
		# Do this to wind up Sam, Fix it, after he picks it up.
		->orderBy('created_at', 'desc')
		->get();

		$chart_months = $months->reverse();
		# Init Chart Array
		$chart = array();

		# Make Stacked Chart Array from Data for ChartJS.
		foreach($chart_months as $month) {

			$chart[$month->month . "-" . $month->year] = [
				'packages_delivered' => $month->packages_delivered,
				'packages_removed' => $month->packages_removed,
			];

		}

		$paid = PackageManagerPayments::all();

		return view('packages.metrics_index', compact('months', 'chart', 'paid'));
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
			COUNT( (CASE WHEN at_action = "Stored Structure Meta Data" THEN at_action END) ) AS structure_meta_data_added,
			COUNT( (CASE WHEN at_action = "Structure Destroyed" THEN at_action END) ) AS structure_destroyed,
			COUNT( (CASE WHEN at_action = "Structure has a Fit" THEN at_action END) ) AS structure_fitted
			')
		->groupBy('at_username')
		->orderBy('at_username', 'asc')
		->get();

		$is_paid = PackageManagerPayments::where('month_year', $month_year)
		->where('paid', 1)
		->first();

		# Init Chart Array
		$chart = array();

		# Make Stacked Chart Array from Data for ChartJS.
		foreach($results as $account) {

			$chart[$account->at_username] = [
				'packages_delivered' => $account->packages_delivered,
				'packages_removed' => $account->packages_removed,
			];

		}

		return view('packages.metrics_monthly_index', compact('results', 'chart', 'month_year', 'is_paid'));
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
		->whereIn('at_action', $query)
		->selectRaw(
			'
			at_username as at_username,
			COUNT( (CASE WHEN at_action = "Package Delivered" THEN at_action END) ) AS packages_delivered,
			COUNT( (CASE WHEN at_action = "Package Removed" THEN at_action END) ) AS packages_removed,
			DAY(created_at) as day
			')
		->groupBy('day')
		->orderBy('day', 'asc')
		->get();

		# Init Chart Array
		$chart = array();

		foreach($results as $account) {
			$chart[$account->day] = [
				'packages_delivered' => $account->packages_delivered,
				'packages_removed' => $account->packages_removed,
				'day' => $account->day,
			]; 
		} 


		# Make Stacked Chart Array from Data for ChartJS.

		return view('packages.month_user_report', compact('results', 'chart', 'month_year', 'at_username'));

	}

	public function export_monthly_stats($month_year)
	{

		$owner = Auth::user();

		list($month, $year) = explode("-", $month_year);

		$month_number = Carbon::parse('1'. $month)->month;
		$days_of_month = Carbon::parse('1'. $month)->daysInMonth;

		$query = [
			'Package Delivered',
			'Package Removed'
		];

		# Run Query to get the goodies.
		$package_payments = ActivityTracker::whereMonth('created_at', '=', $month_number)
		->whereYear('created_at', '=', $year)
		->selectRaw(
			'
			YEAR(created_at) year, 
			MONTHNAME(created_at) month, 
			at_username as at_username,
			COUNT(*) activity, 
			COUNT( (CASE WHEN at_action = "Package Delivered" THEN at_action END) ) AS packages_delivered,
			COUNT( (CASE WHEN at_action = "Package Removed" THEN at_action END) ) AS packages_removed,
			COUNT( (CASE WHEN at_action = "Package Delivered" THEN at_action END) ) - COUNT( (CASE WHEN at_action = "Package Removed" THEN at_action END) ) AS total_packages,
			COUNT( (CASE WHEN at_action = "Package Delivered" THEN at_action END) ) * 3000000 - COUNT( (CASE WHEN at_action = "Package Removed" THEN at_action END)) * 3000000 AS total_amount_due 
			')
		->groupBy('at_username')
		->orderBy('at_username', 'asc')
		->get();


		$filename = '../storage/package_payments/Package_Payment_Report_Export_' . $owner->username . '_' . $month_year . '_' . date("Y-m-d") . '.csv';
		$fields = array('Year', 'Month', 'Username', 'Total Activity', 'Package Delivered', 'Package Removed', 'Total Packages', 'Amount Due', 'Paid');
		$export_data = $package_payments->toArray();

            // file creation
		$file = fopen($filename,"w");

            // Add Headers
		fputcsv($file, $fields);


		foreach ($export_data as $line){
			fputcsv($file, $line);
		}

		fclose($file);

         // download
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=".$filename);
		header("Content-Type: application/csv; "); 

		readfile($filename);

       	 // deleting file
		//unlink($filename);
		exit();


	}

	public function mark_month_as_paid($month_year) {

		$pay_month = new PackageManagerPayments;
		$pay_month->month_year = $month_year;
		$pay_month->paid = 1;
		$pay_month->save();

		return redirect()->back()->withSuccess($month_year . 'has been marked as paid.');

	}




}
