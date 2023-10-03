<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Vanguard\KnownStructures;

use Input;

class StructureStatisticsController extends Controller
{
	public function index() {

		$input_alliance = Input::input('alliance');
		$input_corporation = Input::input('corporation');
		$input_region = Input::input('region');
		$input_system = Input::input('system');
		$input_constellation = Input::input('constellation');
		$input_abandoned = Input::input('abandoned');

		$search = Input::input('search');

		$query = KnownStructures::query();

		if ($input_alliance) {
			$query->where('str_owner_alliance_name', $input_alliance);
		}

		if ($input_corporation) {
			$query->where('str_owner_corporation_name', $input_corporation);
		}

		if ($input_region) {
			$query->where('str_region_name', $input_region);
		}

		if ($input_system) {
			$query->where('str_system', $input_system);
		}

		if ($input_constellation) {
			$query->where('str_constellation_name', $input_constellation);
		}

		if ($input_abandoned == "Yes") {
			$query->where('str_state', "Abandoned");
		}

		if ($search) {
			$query->where(function ($q) use ($search) {
				$q->where('str_owner_alliance_name', "like", "%{$search}%");

			});
		}

		$query->where('str_destroyed', 0);

		$query->selectRaw(
			'
			COUNT( (CASE WHEN str_type = "Astrahus" THEN str_type END) ) AS Astrahus,
			COUNT( (CASE WHEN str_type = "Athanor" THEN str_type END) ) AS Athanor,
			COUNT( (CASE WHEN str_type = "Azbel" THEN str_type END) ) AS Azbel,
			COUNT( (CASE WHEN str_type = "Fortizar" THEN str_type END) ) AS Fortizar,
			COUNT( (CASE WHEN str_type LIKE "%Draccous%" THEN str_type END) ) AS "Draccous Fortizar",
			COUNT( (CASE WHEN str_type LIKE "%Horizon%" THEN str_type END) ) AS "Horizon Fortizar",
			COUNT( (CASE WHEN str_type LIKE "%Marginis%" THEN str_type END) ) AS "Marginis Fortizar",
			COUNT( (CASE WHEN str_type LIKE "%Moreau%" THEN str_type END) ) AS "Moreau Fortizar",
			COUNT( (CASE WHEN str_type LIKE "%Prometheus%" THEN str_type END) ) AS "Prometheus Fortizar",
			COUNT( (CASE WHEN str_type = "Keepstar" THEN str_type END) ) AS Keepstar,
			COUNT( (CASE WHEN str_type = "Raitaru" THEN str_type END) ) AS Raitaru,
			COUNT( (CASE WHEN str_type = "Sotiyo" THEN str_type END) ) AS Sotiyo,
			COUNT( (CASE WHEN str_type = "Tatara" THEN str_type END) ) AS Tatara
			');

		$structures = $query
		->get();

		if ($search) {
			$structures->appends(['search' => $search]);
		}

		# Init Chart Array
		$chart = array();

		$list = $structures->toArray();

		foreach($list as $meh) {

			foreach ($meh as $key => $structure) {

				$chart[$key] = [
					$structure,
				];

			}

		}

		return view('structure_statistics.index', compact('chart'));
	}
}
