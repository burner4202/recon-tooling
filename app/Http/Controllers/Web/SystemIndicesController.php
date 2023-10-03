<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Vanguard\SystemCostIndices;
use Carbon\Carbon;

use Input;

class SystemIndicesController extends Controller
{
	public function index() {

		// Get System Index for Yesterday

		$search = Input::input('search');
		$input_per_page = Input::input('no_per_page');
		$input_security_status = Input::input('security_status');

		$security_status = 		[
			'' => 'All',
			'Highsec' => 'Highsec',
			'Lowsec' => 'Lowsec',
			'Nullsec' => 'Nullsec',

		];


		$no_per_page = 	['100' => '100', '500' => '500', '1000' => '1000'];

		if($input_per_page == "") { $per_page = 100; } else { $per_page = $input_per_page; }

		$now = Carbon::now();
		$yesterday = $now->format('Y-m-d');

		$query = SystemCostIndices::query();


		if ($input_security_status == "Highsec") {
			$query->where('sci_security_status', '>=', '0.50');
		}

		if ($input_security_status == "Lowsec") {
			$query->whereBetween('sci_security_status', ['0.01', '0.49']);
		}

			if ($input_security_status == "Nullsec") {
			$query->where('sci_security_status', '<=', '0');
		}

		if ($search) {
			$query->where(function ($q) use ($search) {
				$q->sortable();
				$q->where('sci_solar_system_name', "like", "%{$search}%");
				$q->orWhere('sci_solar_constellation_name', 'like', "%{$search}%");
				$q->orWhere('sci_solar_region_name', 'like', "%{$search}%");
			});
		}

		$indices = $query
		->where('sci_date', $yesterday)
		->sortable()
		->orderBy('sci_manufacturing', 'DESC')
		->paginate($per_page);

		if ($search) {
			$indices->appends(['search' => $search]);
		}


		return view('indices.index', compact('indices', 'security_status', 'no_per_page'));


	}
}
