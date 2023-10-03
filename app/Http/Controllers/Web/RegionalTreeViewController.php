<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Vanguard\KnownStructures;
use Storage;


class RegionalTreeViewController extends Controller
{
    public function index() {

		$regions = KnownStructures::where('str_destroyed', 0)
		->groupBy('str_region_name')
		->where('str_owner_alliance_id', '>', 1)
		->where('str_type', 'Keepstar')
		->get();


		# Get the Keepstars



		# Cycle each region with a keep star and pick up the region.
		foreach($regions as $region) {


			# Get the alliances in the region with a keepstar
			$alliances_with_keepstar = KnownStructures::where('str_destroyed', 0)
			->where('str_region_name', $region->str_region_name)
			->where('str_owner_alliance_id', '>', 1)
			->where('str_type', 'Keepstar')
			->groupBy('str_owner_alliance_id')
			->get();

			# Populate alliance name level in each region.
			foreach($alliances_with_keepstar as $alliance_name) {

			# Get the keepstar name/system for each alliance in each region
				$has_keepstar = KnownStructures::where('str_destroyed', 0)
				->where('str_region_name', $region->str_region_name)
				->where('str_owner_alliance_id', $alliance_name->str_owner_alliance_id)
				->where('str_type', 'Keepstar')
				->get();

			# Populate each keepstar in each region for each alliance.
				foreach($has_keepstar as $keepstar) {
					$alliance_has_keepstar[] = [
						'name' => $keepstar->str_name,

					];
				}

				$alliance_names[] = [
					'name' => $alliance_name->str_owner_alliance_name,
					'children' => $alliance_has_keepstar,
				];

				unset($alliance_has_keepstar);
			}

			$known_regions[] = [
				'name' => $region->str_region_name,
				'children' => $alliance_names,
			];

			unset($alliance_names);
			

		}

		$d3 = [
			'name' => 'Regions',
			'children' => $known_regions,
		];

		$filename = 'assets/js/d3/alliances/alliances.json';

        // file creation

		file_put_contents($filename, json_encode($d3));

		//Storage::put('d3/region/pure_blind.json', json_encode($d3));

		return view('regional_treeview.index');
	}
}
