<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Input;
use Auth;
use Exception;
use DB;
use Vanguard\ESITokens;
use Vanguard\Characters;
use Vanguard\Ledger;
use Vanguard\SolarSystems;
use Vanguard\HarvestedMaterials;
use Vanguard\RefinedMaterials;
use Vanguard\MarketPrices;
use Vanguard\MoonScans;
use Vanguard\Moons;
use Vanguard\NewMoons;
use Vanguard\MoonCompare;
use Vanguard\Jobs;
use Vanguard\KnownStructures;

use Seat\Eseye\Cache\NullCache;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;
use Seat\Eseye\Exceptions\InvalidContainerDataException;
use Seat\Eseye\Exceptions\RequestFailedException;

use Vanguard\Jobs\parseADASHData;
use Vanguard\Jobs\parseMoonData;

use Queue;

class MoonScanController extends Controller
{

	public function index() 
	{

		$moons = MoonScans::sortable()
		->orderBy('updated_at', 'DESC')
		->paginate(20);

		return view('moons.index', compact('moons'));
	}

	public function new_moons() 
	{

		$search = Input::input('search');

		$input_region = Input::input('region');
		$input_constellation = Input::input('constellation');
		$input_system = Input::input('system');

		// Check Boxes
		$check_r64 = Input::input('r64');
		$check_r32 = Input::input('r32');
		$check_r16 = Input::input('r16');
		$check_r8 = Input::input('r8');
		$check_r4 = Input::input('r4');

		$query = NewMoons::query();

		if ($input_system) {
			$query->where('moon_system_name', $input_system);
		}

		if ($input_region) {
			$query->where('moon_region_name', $input_region);
		}

		if ($input_constellation) {
			$query->where('moon_constellation_name', $input_constellation);
		}

		if ($search) {
			$query->where(function ($q) use ($search) {
				$q->sortable();
				$q->where('moon_name', "like", "%{$search}%");
				$q->orWhere('moon_system_name', 'like', "%{$search}%");
				$q->orWhere('moon_constellation_name', 'like', "%{$search}%");
				$q->orWhere('moon_region_name', 'like', "%{$search}%");
			});
		}

		if ($check_r64 && $check_r32) {
			$query->where(function ($q) {
				$q->sortable();
				$q->where('moon_r_rating', 64);
				$q->orWhere('moon_r_rating', 32);
			});
		} elseif ($check_r64) {
			$query->where(function ($q) {
				$q->sortable();
				$q->where('moon_r_rating', 64);
			});
		} elseif ($check_r32) {
			$query->where(function ($q) {
				$q->sortable();
				$q->where('moon_r_rating', 32);
			});
		}

		if ($check_r16) {
			$query->where('moon_r_rating', 16);
		}

		if ($check_r8) {
			$query->where('moon_r_rating', 8);
		}

		if ($check_r4) {
			$query->where('moon_r_rating', 4);
		}

		$moons = $query
		->sortable()
		->orderBy('moon_value_30_day', 'DESC')
		->leftjoin('known_structures', function($join)
		{
			$join->on('new_moons.moon_name', '=', 'known_structures.str_moon')
			->where('known_structures.str_destroyed', 0);
		})
		->paginate(500);

		if ($search) {
			$moons->appends(['search' => $search]);
		}

		return view('moons.moons', compact('moons'));

	}


	public function old_moons() 
	{

		$search = Input::input('search');

		$input_region = Input::input('region');
		$input_constellation = Input::input('constellation');
		$input_system = Input::input('system');

		// Check Boxes
		$check_r64 = Input::input('r64');
		$check_r32 = Input::input('r32');
		$check_r16 = Input::input('r16');
		$check_r8 = Input::input('r8');
		$check_r4 = Input::input('r4');

		$query = Moons::query();

		if ($input_system) {
			$query->where('moon_system_name', $input_system);
		}

		if ($input_region) {
			$query->where('moon_region_name', $input_region);
		}

		if ($input_constellation) {
			$query->where('moon_constellation_name', $input_constellation);
		}


		if ($check_r64) {
			$query->where('moon_r_rating', 64);
		}

		if ($check_r32) {
			$query->where('moon_r_rating', 32);
		}

		if ($check_r16) {
			$query->where('moon_r_rating', 16);
		}

		if ($check_r8) {
			$query->where('moon_r_rating', 8);
		}

		if ($check_r4) {
			$query->where('moon_r_rating', 4);
		}

		if ($search) {
			$query->where(function ($q) use ($search) {
				$q->sortable();
				$q->where('moon_name', "like", "%{$search}%");
				$q->orWhere('moon_system_name', 'like', "%{$search}%");
				$q->orWhere('moon_constellation_name', 'like', "%{$search}%");
				$q->orWhere('moon_region_name', 'like', "%{$search}%");
			});
		}

		$moons = $query
		->sortable()
		->orderBy('moon_value_30_day', 'DESC')
		->paginate(100);

		if ($search) {
			$moons->appends(['search' => $search]);
		}

		return view('moons.old_moons', compact('moons'));

	}

	public function moons_compare() 
	{

		$search = Input::input('search');

		$input_region = Input::input('region');
		$input_constellation = Input::input('constellation');
		$input_system = Input::input('system');

		$query = MoonCompare::query();

		if ($input_system) {
			$query->where('moon_system_name', $input_system);
		}

		if ($input_region) {
			$query->where('moon_region_name', $input_region);
		}

		if ($input_constellation) {
			$query->where('moon_constellation_name', $input_constellation);
		}

		if ($search) {
			$query->where(function ($q) use ($search) {
				$q->sortable();
				$q->where('moon_name', "like", "%{$search}%");
				$q->orWhere('moon_system_name', 'like', "%{$search}%");
				$q->orWhere('moon_constellation_name', 'like', "%{$search}%");
				$q->orWhere('moon_region_name', 'like', "%{$search}%");
			});
		}

		$moons = $query
		->sortable()
		->orderBy('moon_new_value_56_day', 'DESC')
		->where('moon_old_value_56_day', '>', 0)
		->paginate(200);

		if ($search) {
			$moons->appends(['search' => $search]);
		}

		return view('moons.moons_compare', compact('moons'));

	}

	public function view_new_moon($moon_id) {

		if(is_numeric($moon_id)) {
			$moon = NewMoons::where('moon_id', $moon_id)->first();
		} else {
			$moon = NewMoons::where('moon_name', $moon_id)->first();
		}

		if($moon->moon_value_30_day == "") {
			## Is there a structure on this moon?
			$structure = KnownStructures::where('str_moon', $moon->moon_name)->where('str_destroyed', 0)->first();
			return view('moons.empty_moon', compact('moon', 'structure'));
		}

		$products = $moon->moon_dist_ore;

		$pie_chart_product = array();
		$pie_chart_mineral = array();
		$chartColoursProduct = array();
		$chartColoursMineral = array();

		foreach(json_decode($products) as $minerals) {

			foreach($minerals->refined as $get_goo) {

				$colour_one = rand(0,255);
				$colour_two = rand(0,255);
				$colour_three = rand(0,255);
				$transparency = 0.9;
				$colourScheme = "rgba(" . $colour_one . "," . $colour_two . "," . $colour_three . "," . $transparency .")";

				$chartColoursMineral[$get_goo->name] =  $colourScheme;

				$pie_chart_mineral[$get_goo->name] =+ round($get_goo->refine_amount_per_hour ,2);
			}

		}

		foreach(json_decode($products) as $goo) {

			$colour_one = rand(0,255);
			$colour_two = rand(0,255);
			$colour_three = rand(0,255);
			$transparency = 0.9;
			$colourScheme = "rgba(" . $colour_one . "," . $colour_two . "," . $colour_three . "," . $transparency .")";

			$chartColoursProduct[$goo->name] =  $colourScheme;

			$pie_chart_product[$goo->name] = $goo->distribution * 100;
		}

		## Is there a structure on this moon?

		$structure = KnownStructures::where('str_moon', $moon->moon_name)->where('str_destroyed', 0)->first();


		return view('moons.view_moon', compact('moon', 'pie_chart_product', 'chartColoursProduct', 'pie_chart_mineral', 'chartColoursMineral', 'structure'));
	}

	public function view_old_moon($moon_id) {

		if(is_numeric($moon_id)) {
			$moon = Moons::where('moon_id', $moon_id)->first();
		} else {
			$moon = Moons::where('moon_name', $moon_id)->first();
		}

		if($moon->moon_value_30_day == "") {
			return view('moons.empty_moon', compact('moon'));
		}

		$products = $moon->moon_dist_ore;

		$pie_chart_product = array();
		$pie_chart_mineral = array();
		$chartColoursProduct = array();
		$chartColoursMineral = array();

		foreach(json_decode($products) as $minerals) {

			foreach($minerals->refined as $get_goo) {

				$colour_one = rand(0,255);
				$colour_two = rand(0,255);
				$colour_three = rand(0,255);
				$transparency = 0.9;
				$colourScheme = "rgba(" . $colour_one . "," . $colour_two . "," . $colour_three . "," . $transparency .")";

				$chartColoursMineral[$get_goo->name] =  $colourScheme;

				$pie_chart_mineral[$get_goo->name] =+ round($get_goo->refine_amount_per_hour ,2);
			}

		}

		foreach(json_decode($products) as $goo) {

			$colour_one = rand(0,255);
			$colour_two = rand(0,255);
			$colour_three = rand(0,255);
			$transparency = 0.9;
			$colourScheme = "rgba(" . $colour_one . "," . $colour_two . "," . $colour_three . "," . $transparency .")";

			$chartColoursProduct[$goo->name] =  $colourScheme;

			$pie_chart_product[$goo->name] = $goo->distribution * 100;
		}



		return view('moons.view_old_moon', compact('moon', 'pie_chart_product', 'chartColoursProduct', 'pie_chart_mineral', 'chartColoursMineral'));
	}

	public function regional_view($region_id) {

		$region = DB::table('new_moons')

		->select(
			DB::raw('moon_region_name as moon_region_name'),
			DB::raw('moon_region_id as moon_region_id'),
			DB::raw('sum(moon_value_24_hour) as regional_value_24_hour'),
			DB::raw('sum(moon_value_7_day) as regional_value_7_day'),
			DB::raw('sum(moon_value_30_day) as regional_value_30_day'),
			DB::raw('COUNT( (CASE WHEN moon_region_id > 1 THEN moon_region_id END) ) AS total_moons'),
			DB::raw('COUNT( (CASE WHEN moon_value_24_hour > 1 THEN moon_value_24_hour END) ) AS scanned_moons'),
			DB::raw('COUNT( (CASE WHEN moon_r_rating = 64 THEN moon_r_rating END) ) AS r64'),
			DB::raw('COUNT( (CASE WHEN moon_r_rating = 32 THEN moon_r_rating END) ) AS r32'),
			DB::raw('COUNT( (CASE WHEN moon_r_rating = 16 THEN moon_r_rating END) ) AS r16'),
			DB::raw('COUNT( (CASE WHEN moon_r_rating = 8 THEN moon_r_rating END) ) AS r8'),
			DB::raw('COUNT( (CASE WHEN moon_r_rating = 4 THEN moon_r_rating END) ) AS r4'),
			DB::raw('COUNT( (CASE WHEN moon_atmo_gases = 1 THEN moon_atmo_gases END) ) AS atmo_gases'),
			DB::raw('COUNT( (CASE WHEN moon_cadmium = 1 THEN moon_cadmium END) ) AS cadmium'),
			DB::raw('COUNT( (CASE WHEN moon_caesium = 1 THEN moon_caesium END) ) AS caesium'),
			DB::raw('COUNT( (CASE WHEN moon_chromium = 1 THEN moon_chromium END) ) AS chromium'),
			DB::raw('COUNT( (CASE WHEN moon_cobalt = 1 THEN moon_cobalt END) ) AS cobalt'),
			DB::raw('COUNT( (CASE WHEN moon_dysprosium = 1 THEN moon_dysprosium END) ) AS dysprosium'),
			DB::raw('COUNT( (CASE WHEN moon_eva_depo = 1 THEN moon_eva_depo END) ) AS eva_depo'),
			DB::raw('COUNT( (CASE WHEN moon_hafnium = 1 THEN moon_hafnium END) ) AS hafnium'),
			DB::raw('COUNT( (CASE WHEN moon_hydrocarbons = 1 THEN moon_hydrocarbons END) ) AS hydrocarbons'),
			DB::raw('COUNT( (CASE WHEN moon_mercury = 1 THEN moon_mercury END) ) AS mercury'),
			DB::raw('COUNT( (CASE WHEN moon_neodymium = 1 THEN moon_neodymium END) ) AS neodymium'),
			DB::raw('COUNT( (CASE WHEN moon_platinum = 1 THEN moon_platinum END) ) AS platinum'),
			DB::raw('COUNT( (CASE WHEN moon_promethium = 1 THEN moon_promethium END) ) AS promethium'),
			DB::raw('COUNT( (CASE WHEN moon_scandium = 1 THEN moon_scandium END) ) AS scandium'),
			DB::raw('COUNT( (CASE WHEN moon_silicates = 1 THEN moon_silicates END) ) AS silicates'),
			DB::raw('COUNT( (CASE WHEN moon_technetium = 1 THEN moon_technetium END) ) AS technetium'),
			DB::raw('COUNT( (CASE WHEN moon_thulium = 1 THEN moon_thulium END) ) AS thulium'),
			DB::raw('COUNT( (CASE WHEN moon_titanium = 1 THEN moon_titanium END) ) AS titanium'),
			DB::raw('COUNT( (CASE WHEN moon_tungsten = 1 THEN moon_tungsten END) ) AS tungsten'),
			DB::raw('COUNT( (CASE WHEN moon_vanadium = 1 THEN moon_vanadium END) ) AS vanadium')
		)
		->where('moon_region_id', $region_id)
		->groupBy('moon_region_name')
		->orderBy('moon_region_name', 'ASC')
		->first();

		## July 2021 Adding System Breakdown

		$systems = NewMoons::where('moon_region_id', $region_id)->groupBy('moon_system_name')->orderBy('moon_system_name', 'ASC')->get();
		$system_data = array();

		foreach($systems as $system) {

			$system_moon_data = DB::table('new_moons')

			->select(
				DB::raw('moon_system_name as moon_system_name'),
				DB::raw('moon_system_id as moon_system_id'),
				DB::raw('moon_constellation_name as moon_constellation_name'),
				DB::raw('moon_constellation_id as moon_constellation_id'),
				DB::raw('moon_region_name as moon_region_name'),
				DB::raw('moon_region_id as moon_region_id'),
				DB::raw('sum(moon_value_24_hour) as system_value_24_hour'),
				DB::raw('sum(moon_value_7_day) as system_value_7_day'),
				DB::raw('sum(moon_value_30_day) as system_value_30_day'),
				DB::raw('sum(moon_value_56_day) as system_value_56_day'),
				DB::raw('COUNT( (CASE WHEN moon_region_id > 1 THEN moon_region_id END) ) AS total_moons'),
				DB::raw('COUNT( (CASE WHEN moon_value_24_hour > 1 THEN moon_value_24_hour END) ) AS scanned_moons'),
				DB::raw('COUNT( (CASE WHEN moon_r_rating = 64 THEN moon_r_rating END) ) AS r64'),
				DB::raw('COUNT( (CASE WHEN moon_r_rating = 32 THEN moon_r_rating END) ) AS r32'),
				DB::raw('COUNT( (CASE WHEN moon_r_rating = 16 THEN moon_r_rating END) ) AS r16'),
				DB::raw('COUNT( (CASE WHEN moon_r_rating = 8 THEN moon_r_rating END) ) AS r8'),
				DB::raw('COUNT( (CASE WHEN moon_r_rating = 4 THEN moon_r_rating END) ) AS r4'),
				DB::raw('COUNT( (CASE WHEN moon_atmo_gases = 1 THEN moon_atmo_gases END) ) AS atmo_gases'),
				DB::raw('COUNT( (CASE WHEN moon_cadmium = 1 THEN moon_cadmium END) ) AS cadmium'),
				DB::raw('COUNT( (CASE WHEN moon_caesium = 1 THEN moon_caesium END) ) AS caesium'),
				DB::raw('COUNT( (CASE WHEN moon_chromium = 1 THEN moon_chromium END) ) AS chromium'),
				DB::raw('COUNT( (CASE WHEN moon_cobalt = 1 THEN moon_cobalt END) ) AS cobalt'),
				DB::raw('COUNT( (CASE WHEN moon_dysprosium = 1 THEN moon_dysprosium END) ) AS dysprosium'),
				DB::raw('COUNT( (CASE WHEN moon_eva_depo = 1 THEN moon_eva_depo END) ) AS eva_depo'),
				DB::raw('COUNT( (CASE WHEN moon_hafnium = 1 THEN moon_hafnium END) ) AS hafnium'),
				DB::raw('COUNT( (CASE WHEN moon_hydrocarbons = 1 THEN moon_hydrocarbons END) ) AS hydrocarbons'),
				DB::raw('COUNT( (CASE WHEN moon_mercury = 1 THEN moon_mercury END) ) AS mercury'),
				DB::raw('COUNT( (CASE WHEN moon_neodymium = 1 THEN moon_neodymium END) ) AS neodymium'),
				DB::raw('COUNT( (CASE WHEN moon_platinum = 1 THEN moon_platinum END) ) AS platinum'),
				DB::raw('COUNT( (CASE WHEN moon_promethium = 1 THEN moon_promethium END) ) AS promethium'),
				DB::raw('COUNT( (CASE WHEN moon_scandium = 1 THEN moon_scandium END) ) AS scandium'),
				DB::raw('COUNT( (CASE WHEN moon_silicates = 1 THEN moon_silicates END) ) AS silicates'),
				DB::raw('COUNT( (CASE WHEN moon_technetium = 1 THEN moon_technetium END) ) AS technetium'),
				DB::raw('COUNT( (CASE WHEN moon_thulium = 1 THEN moon_thulium END) ) AS thulium'),
				DB::raw('COUNT( (CASE WHEN moon_titanium = 1 THEN moon_titanium END) ) AS titanium'),
				DB::raw('COUNT( (CASE WHEN moon_tungsten = 1 THEN moon_tungsten END) ) AS tungsten'),
				DB::raw('COUNT( (CASE WHEN moon_vanadium = 1 THEN moon_vanadium END) ) AS vanadium')
			)
			->where('moon_system_name', $system->moon_system_name)
			->first();

			$system_data[$system->moon_system_name] = $system_moon_data;

		}

		//$top_20_value_2020 = NewMoons::orderBy('moon_value_30_day', 'DESC')->where('moon_region_id', $region_id)->take(20)->get();

		$r64 = array();
		$r32 = array();
		$r16 = array();
		$r8 = array();
		$r4 = array();

		$r64_sum = $region->dysprosium + $region->neodymium + $region->promethium + $region->thulium;
		$r64['Dysprosium ('.$region->dysprosium.')'] = number_format($region->dysprosium / $r64_sum * 100,2);
		$r64['Neodymium ('.$region->neodymium.')'] = number_format($region->neodymium / $r64_sum * 100,2);
		$r64['Promethium ('.$region->promethium.')'] = number_format($region->promethium / $r64_sum * 100,2);
		$r64['Thulium ('.$region->thulium.')'] = number_format($region->thulium / $r64_sum * 100,2);

		$r64_colours['Dysprosium ('.$region->dysprosium.')'] = "rgba(0, 0, 0, 0.5)";
		$r64_colours['Neodymium ('.$region->neodymium.')'] = "rgba(60, 180, 75, 0.5)";
		$r64_colours['Promethium ('.$region->promethium.')'] = "rgba(0, 130, 200, 0.5)";
		$r64_colours['Thulium ('.$region->thulium.')'] = "rgba(250, 190, 190, 0.5)";

		$r32_sum = $region->caesium + $region->hafnium + $region->mercury  + $region->technetium;
		$r32['Caesium ('.$region->caesium.')'] = number_format($region->caesium / $r32_sum * 100,2);
		$r32['Hafnium ('.$region->hafnium.')'] = number_format($region->hafnium / $r32_sum * 100,2);
		$r32['Mercury ('.$region->mercury.')'] = number_format($region->mercury / $r32_sum * 100,2);
		$r32['Technetium ('.$region->technetium.')'] = number_format($region->technetium / $r32_sum * 100,2);

		$r32_colours['Caesium ('.$region->caesium.')'] = "rgba(128, 128, 0, 0.5)";
		$r32_colours['Hafnium ('.$region->hafnium.')'] = "rgba(245, 130, 48, 0.5)";
		$r32_colours['Mercury ('.$region->mercury.')'] = "rgba(210, 245, 60, 0.5)";
		$r32_colours['Technetium ('.$region->technetium.')'] = "rgba(128, 128, 128, 0.5)";

		$r16_sum = $region->cadmium + $region->chromium + $region->platinum + $region->vanadium;
		$r16['Cadmium ('.$region->cadmium.')'] = number_format($region->cadmium / $r16_sum * 100,2);
		$r16['Chromium ('.$region->chromium.')'] = number_format($region->chromium / $r16_sum * 100,2);
		$r16['Platinum ('.$region->platinum.')'] = number_format($region->platinum / $r16_sum * 100,2);
		$r16['Vanadium ('.$region->vanadium.')'] = number_format($region->vanadium / $r16_sum * 100,2);

		$r16_colours['Cadmium ('.$region->cadmium.')'] = "rgba(170, 110, 40, 0.5)";
		$r16_colours['Chromium ('.$region->chromium.')'] = "rgba(0, 128, 128, 0.5)";
		$r16_colours['Platinum ('.$region->platinum.')'] = "rgba(70, 240, 240, 0.5)";
		$r16_colours['Vanadium ('.$region->vanadium.')'] = "rgba(255, 215, 180, 0.5)";

		$r8_sum = $region->cobalt + $region->scandium + $region->titanium + $region->tungsten;
		$r8['Cobalt ('.$region->cobalt.')'] = number_format($region->cobalt / $r8_sum * 100,2);
		$r8['Scandium ('.$region->scandium.')'] = number_format($region->scandium / $r8_sum * 100,2);
		$r8['Titanium ('.$region->titanium.')'] = number_format($region->titanium / $r8_sum * 100,2);
		$r8['Tungsten ('.$region->tungsten.')'] = number_format($region->tungsten / $r8_sum * 100,2);

		$r8_colours['Cobalt ('.$region->cobalt.')'] = "rgba(0, 0, 128, 0.5)";
		$r8_colours['Scandium ('.$region->scandium.')'] = "rgba(145, 30, 180, 0.5)";
		$r8_colours['Titanium ('.$region->titanium.')'] = "rgba(170, 255, 195, 0.5)";
		$r8_colours['Tungsten ('.$region->tungsten.')'] = "rgba(230, 190, 255, 0.5)";

		$r4_sum = $region->atmo_gases + $region->eva_depo + $region->hydrocarbons + $region->silicates;
		$r4['Atmospheric Gases ('.$region->atmo_gases.')'] = number_format($region->atmo_gases / $r4_sum * 100,2);
		$r4['Evaporite Deposits ('.$region->eva_depo.')'] = number_format($region->eva_depo / $r4_sum * 100,2);
		$r4['Hydrocarbons ('.$region->hydrocarbons.')'] = number_format($region->hydrocarbons / $r4_sum * 100,2);
		$r4['Silicates ('.$region->silicates.')'] = number_format($region->silicates / $r4_sum * 100,2);

		$r4_colours['Atmospheric Gases ('.$region->atmo_gases.')'] = "rgba(128, 0, 0, 0.5)";
		$r4_colours['Evaporite Deposits ('.$region->eva_depo.')'] = "rgba(230, 25, 75, 0.5)";
		$r4_colours['Hydrocarbons ('.$region->hydrocarbons.')'] = "rgba(255, 225, 25, 0.5)";
		$r4_colours['Silicates ('.$region->silicates.')'] = "rgba(240, 50, 230, 0.5)";

		$region_name = $region->moon_region_name;

		$r64_value = NewMoons::where('moon_r_rating', '64')->where('moon_region_id', $region_id)->sum('moon_value_24_hour');
		$r32_value = NewMoons::where('moon_r_rating', '32')->where('moon_region_id', $region_id)->sum('moon_value_24_hour');
		$r16_value = NewMoons::where('moon_r_rating', '16')->where('moon_region_id', $region_id)->sum('moon_value_24_hour');
		$r8_value = NewMoons::where('moon_r_rating', '8')->where('moon_region_id', $region_id)->sum('moon_value_24_hour');
		$r4_value = NewMoons::where('moon_r_rating', '4')->where('moon_region_id', $region_id)->sum('moon_value_24_hour');
		$r0_value = NewMoons::where('moon_r_rating', '')->where('moon_region_id', $region_id)->sum('moon_value_24_hour');

		$rarity_value = collect([
			'r64_56_day_value' => ($r64_value * 56),
			'r32_56_day_value' => ($r32_value * 56),
			'r16_56_day_value' => ($r16_value * 56),
			'r8_56_day_value'  => ($r8_value * 56),
			'r4_56_day_value'  => ($r4_value * 56),
			'r0_56_day_value'  => ($r0_value * 56),
		]);

		return view('moons.regional_view', compact(
			'region_name',
			'region',
			'chart_stacked',
			'r64',
			'r64_colours',
			'r32',
			'r32_colours',
			'r16',
			'r16_colours',
			'r8',
			'r8_colours',
			'r4',
			'r4_colours',
			'top_20_value_2020',
			'rarity_value',
			'system_data'
		));
	}

	public function regional_old_view($region_id) {

		$region = DB::table('moons')

		->select(
			DB::raw('moon_region_name as moon_region_name'),
			DB::raw('moon_region_id as moon_region_id'),
			DB::raw('sum(moon_value_24_hour) as regional_value_24_hour'),
			DB::raw('sum(moon_value_7_day) as regional_value_7_day'),
			DB::raw('sum(moon_value_30_day) as regional_value_30_day'),
			DB::raw('COUNT( (CASE WHEN moon_region_id > 1 THEN moon_region_id END) ) AS total_moons'),
			DB::raw('COUNT( (CASE WHEN moon_value_24_hour > 1 THEN moon_value_24_hour END) ) AS scanned_moons'),
			DB::raw('COUNT( (CASE WHEN moon_r_rating = 64 THEN moon_r_rating END) ) AS r64'),
			DB::raw('COUNT( (CASE WHEN moon_r_rating = 32 THEN moon_r_rating END) ) AS r32'),
			DB::raw('COUNT( (CASE WHEN moon_r_rating = 16 THEN moon_r_rating END) ) AS r16'),
			DB::raw('COUNT( (CASE WHEN moon_r_rating = 8 THEN moon_r_rating END) ) AS r8'),
			DB::raw('COUNT( (CASE WHEN moon_r_rating = 4 THEN moon_r_rating END) ) AS r4'),
			DB::raw('COUNT( (CASE WHEN moon_atmo_gases = 1 THEN moon_atmo_gases END) ) AS atmo_gases'),
			DB::raw('COUNT( (CASE WHEN moon_cadmium = 1 THEN moon_cadmium END) ) AS cadmium'),
			DB::raw('COUNT( (CASE WHEN moon_caesium = 1 THEN moon_caesium END) ) AS caesium'),
			DB::raw('COUNT( (CASE WHEN moon_chromium = 1 THEN moon_chromium END) ) AS chromium'),
			DB::raw('COUNT( (CASE WHEN moon_cobalt = 1 THEN moon_cobalt END) ) AS cobalt'),
			DB::raw('COUNT( (CASE WHEN moon_dysprosium = 1 THEN moon_dysprosium END) ) AS dysprosium'),
			DB::raw('COUNT( (CASE WHEN moon_eva_depo = 1 THEN moon_eva_depo END) ) AS eva_depo'),
			DB::raw('COUNT( (CASE WHEN moon_hafnium = 1 THEN moon_hafnium END) ) AS hafnium'),
			DB::raw('COUNT( (CASE WHEN moon_hydrocarbons = 1 THEN moon_hydrocarbons END) ) AS hydrocarbons'),
			DB::raw('COUNT( (CASE WHEN moon_mercury = 1 THEN moon_mercury END) ) AS mercury'),
			DB::raw('COUNT( (CASE WHEN moon_neodymium = 1 THEN moon_neodymium END) ) AS neodymium'),
			DB::raw('COUNT( (CASE WHEN moon_platinum = 1 THEN moon_platinum END) ) AS platinum'),
			DB::raw('COUNT( (CASE WHEN moon_promethium = 1 THEN moon_promethium END) ) AS promethium'),
			DB::raw('COUNT( (CASE WHEN moon_scandium = 1 THEN moon_scandium END) ) AS scandium'),
			DB::raw('COUNT( (CASE WHEN moon_silicates = 1 THEN moon_silicates END) ) AS silicates'),
			DB::raw('COUNT( (CASE WHEN moon_technetium = 1 THEN moon_technetium END) ) AS technetium'),
			DB::raw('COUNT( (CASE WHEN moon_thulium = 1 THEN moon_thulium END) ) AS thulium'),
			DB::raw('COUNT( (CASE WHEN moon_titanium = 1 THEN moon_titanium END) ) AS titanium'),
			DB::raw('COUNT( (CASE WHEN moon_tungsten = 1 THEN moon_tungsten END) ) AS tungsten'),
			DB::raw('COUNT( (CASE WHEN moon_vanadium = 1 THEN moon_vanadium END) ) AS vanadium')
		)
		->where('moon_region_id', $region_id)
		->groupBy('moon_region_name')
		->orderBy('moon_region_name', 'ASC')
		->first();

		$top_20_value_2020 = Moons::orderBy('moon_value_30_day', 'DESC')->where('moon_region_id', $region_id)->take(20)->get();

		$r64 = array();
		$r32 = array();
		$r16 = array();
		$r8 = array();
		$r4 = array();

		$r64_sum = $region->dysprosium + $region->neodymium + $region->promethium + $region->thulium;
		$r64['Dysprosium ('.$region->dysprosium.')'] = number_format($region->dysprosium / $r64_sum * 100,2);
		$r64['Neodymium ('.$region->neodymium.')'] = number_format($region->neodymium / $r64_sum * 100,2);
		$r64['Promethium ('.$region->promethium.')'] = number_format($region->promethium / $r64_sum * 100,2);
		$r64['Thulium ('.$region->thulium.')'] = number_format($region->thulium / $r64_sum * 100,2);

		$r64_colours['Dysprosium ('.$region->dysprosium.')'] = "rgba(0, 0, 0, 0.5)";
		$r64_colours['Neodymium ('.$region->neodymium.')'] = "rgba(60, 180, 75, 0.5)";
		$r64_colours['Promethium ('.$region->promethium.')'] = "rgba(0, 130, 200, 0.5)";
		$r64_colours['Thulium ('.$region->thulium.')'] = "rgba(250, 190, 190, 0.5)";

		$r32_sum = $region->caesium + $region->hafnium + $region->mercury  + $region->technetium;
		$r32['Caesium ('.$region->caesium.')'] = number_format($region->caesium / $r32_sum * 100,2);
		$r32['Hafnium ('.$region->hafnium.')'] = number_format($region->hafnium / $r32_sum * 100,2);
		$r32['Mercury ('.$region->mercury.')'] = number_format($region->mercury / $r32_sum * 100,2);
		$r32['Technetium ('.$region->technetium.')'] = number_format($region->technetium / $r32_sum * 100,2);

		$r32_colours['Caesium ('.$region->caesium.')'] = "rgba(128, 128, 0, 0.5)";
		$r32_colours['Hafnium ('.$region->hafnium.')'] = "rgba(245, 130, 48, 0.5)";
		$r32_colours['Mercury ('.$region->mercury.')'] = "rgba(210, 245, 60, 0.5)";
		$r32_colours['Technetium ('.$region->technetium.')'] = "rgba(128, 128, 128, 0.5)";

		$r16_sum = $region->cadmium + $region->chromium + $region->platinum + $region->vanadium;
		$r16['Cadmium ('.$region->cadmium.')'] = number_format($region->cadmium / $r16_sum * 100,2);
		$r16['Chromium ('.$region->chromium.')'] = number_format($region->chromium / $r16_sum * 100,2);
		$r16['Platinum ('.$region->platinum.')'] = number_format($region->platinum / $r16_sum * 100,2);
		$r16['Vanadium ('.$region->vanadium.')'] = number_format($region->vanadium / $r16_sum * 100,2);

		$r16_colours['Cadmium ('.$region->cadmium.')'] = "rgba(170, 110, 40, 0.5)";
		$r16_colours['Chromium ('.$region->chromium.')'] = "rgba(0, 128, 128, 0.5)";
		$r16_colours['Platinum ('.$region->platinum.')'] = "rgba(70, 240, 240, 0.5)";
		$r16_colours['Vanadium ('.$region->vanadium.')'] = "rgba(255, 215, 180, 0.5)";

		$r8_sum = $region->cobalt + $region->scandium + $region->titanium + $region->tungsten;
		$r8['Cobalt ('.$region->cobalt.')'] = number_format($region->cobalt / $r8_sum * 100,2);
		$r8['Scandium ('.$region->scandium.')'] = number_format($region->scandium / $r8_sum * 100,2);
		$r8['Titanium ('.$region->titanium.')'] = number_format($region->titanium / $r8_sum * 100,2);
		$r8['Tungsten ('.$region->tungsten.')'] = number_format($region->tungsten / $r8_sum * 100,2);

		$r8_colours['Cobalt ('.$region->cobalt.')'] = "rgba(0, 0, 128, 0.5)";
		$r8_colours['Scandium ('.$region->scandium.')'] = "rgba(145, 30, 180, 0.5)";
		$r8_colours['Titanium ('.$region->titanium.')'] = "rgba(170, 255, 195, 0.5)";
		$r8_colours['Tungsten ('.$region->tungsten.')'] = "rgba(230, 190, 255, 0.5)";

		$r4_sum = $region->atmo_gases + $region->eva_depo + $region->hydrocarbons + $region->silicates;
		$r4['Atmospheric Gases ('.$region->atmo_gases.')'] = number_format($region->atmo_gases / $r4_sum * 100,2);
		$r4['Evaporite Deposits ('.$region->eva_depo.')'] = number_format($region->eva_depo / $r4_sum * 100,2);
		$r4['Hydrocarbons ('.$region->hydrocarbons.')'] = number_format($region->hydrocarbons / $r4_sum * 100,2);
		$r4['Silicates ('.$region->silicates.')'] = number_format($region->silicates / $r4_sum * 100,2);

		$r4_colours['Atmospheric Gases ('.$region->atmo_gases.')'] = "rgba(128, 0, 0, 0.5)";
		$r4_colours['Evaporite Deposits ('.$region->eva_depo.')'] = "rgba(230, 25, 75, 0.5)";
		$r4_colours['Hydrocarbons ('.$region->hydrocarbons.')'] = "rgba(255, 225, 25, 0.5)";
		$r4_colours['Silicates ('.$region->silicates.')'] = "rgba(240, 50, 230, 0.5)";

		$region_name = $region->moon_region_name;

		$r64_value = Moons::where('moon_r_rating', '64')->where('moon_region_id', $region_id)->sum('moon_value_24_hour');
		$r32_value = Moons::where('moon_r_rating', '32')->where('moon_region_id', $region_id)->sum('moon_value_24_hour');
		$r16_value = Moons::where('moon_r_rating', '16')->where('moon_region_id', $region_id)->sum('moon_value_24_hour');
		$r8_value = Moons::where('moon_r_rating', '8')->where('moon_region_id', $region_id)->sum('moon_value_24_hour');
		$r4_value = Moons::where('moon_r_rating', '4')->where('moon_region_id', $region_id)->sum('moon_value_24_hour');
		$r0_value = Moons::where('moon_r_rating', '')->where('moon_region_id', $region_id)->sum('moon_value_24_hour');

		$rarity_value = collect([
			'r64_56_day_value' => ($r64_value * 56),
			'r32_56_day_value' => ($r32_value * 56),
			'r16_56_day_value' => ($r16_value * 56),
			'r8_56_day_value'  => ($r8_value * 56),
			'r4_56_day_value'  => ($r4_value * 56),
			'r0_56_day_value'  => ($r0_value * 56),
		]);

		return view('moons.regional_old_view', compact(
			'region_name',
			'region',
			'chart_stacked',
			'r64',
			'r64_colours',
			'r32',
			'r32_colours',
			'r16',
			'r16_colours',
			'r8',
			'r8_colours',
			'r4',
			'r4_colours',
			'top_20_value_2020',
			'rarity_value'
		));
	}

	public function regional_report() {

		$region_stats = DB::table('new_moons')
		->select(
			DB::raw('moon_region_name as moon_region_name'),
			DB::raw('moon_region_id as moon_region_id'),
			DB::raw('sum(moon_value_24_hour) as regional_value_24_hour'),
			DB::raw('sum(moon_value_7_day) as regional_value_7_day'),
			DB::raw('sum(moon_value_30_day) as regional_value_30_day'),
			DB::raw('COUNT( (CASE WHEN moon_region_id > 1 THEN moon_region_id END) ) AS total_moons'),
			DB::raw('COUNT( (CASE WHEN moon_value_24_hour > 1 THEN moon_value_24_hour END) ) AS scanned_moons'),
			DB::raw('COUNT( (CASE WHEN moon_r_rating = 64 THEN moon_r_rating END) ) AS r64'),
			DB::raw('COUNT( (CASE WHEN moon_r_rating = 32 THEN moon_r_rating END) ) AS r32'),
			DB::raw('COUNT( (CASE WHEN moon_r_rating = 16 THEN moon_r_rating END) ) AS r16'),
			DB::raw('COUNT( (CASE WHEN moon_r_rating = 8 THEN moon_r_rating END) ) AS r8'),
			DB::raw('COUNT( (CASE WHEN moon_r_rating = 4 THEN moon_r_rating END) ) AS r4'),
			DB::raw('COUNT( (CASE WHEN moon_atmo_gases = 1 THEN moon_atmo_gases END) ) AS atmo_gases'),
			DB::raw('COUNT( (CASE WHEN moon_cadmium = 1 THEN moon_cadmium END) ) AS cadmium'),
			DB::raw('COUNT( (CASE WHEN moon_caesium = 1 THEN moon_caesium END) ) AS caesium'),
			DB::raw('COUNT( (CASE WHEN moon_chromium = 1 THEN moon_chromium END) ) AS chromium'),
			DB::raw('COUNT( (CASE WHEN moon_cobalt = 1 THEN moon_cobalt END) ) AS cobalt'),
			DB::raw('COUNT( (CASE WHEN moon_dysprosium = 1 THEN moon_dysprosium END) ) AS dysprosium'),
			DB::raw('COUNT( (CASE WHEN moon_eva_depo = 1 THEN moon_eva_depo END) ) AS eva_depo'),
			DB::raw('COUNT( (CASE WHEN moon_hafnium = 1 THEN moon_hafnium END) ) AS hafnium'),
			DB::raw('COUNT( (CASE WHEN moon_hydrocarbons = 1 THEN moon_hydrocarbons END) ) AS hydrocarbons'),
			DB::raw('COUNT( (CASE WHEN moon_mercury = 1 THEN moon_mercury END) ) AS mercury'),
			DB::raw('COUNT( (CASE WHEN moon_neodymium = 1 THEN moon_neodymium END) ) AS neodymium'),
			DB::raw('COUNT( (CASE WHEN moon_platinum = 1 THEN moon_platinum END) ) AS platinum'),
			DB::raw('COUNT( (CASE WHEN moon_promethium = 1 THEN moon_promethium END) ) AS promethium'),
			DB::raw('COUNT( (CASE WHEN moon_scandium = 1 THEN moon_scandium END) ) AS scandium'),
			DB::raw('COUNT( (CASE WHEN moon_silicates = 1 THEN moon_silicates END) ) AS silicates'),
			DB::raw('COUNT( (CASE WHEN moon_technetium = 1 THEN moon_technetium END) ) AS technetium'),
			DB::raw('COUNT( (CASE WHEN moon_thulium = 1 THEN moon_thulium END) ) AS thulium'),
			DB::raw('COUNT( (CASE WHEN moon_titanium = 1 THEN moon_titanium END) ) AS titanium'),
			DB::raw('COUNT( (CASE WHEN moon_tungsten = 1 THEN moon_tungsten END) ) AS tungsten'),
			DB::raw('COUNT( (CASE WHEN moon_vanadium = 1 THEN moon_vanadium END) ) AS vanadium')
		)
		->groupBy('moon_region_name')
		->orderBy('moon_region_name', 'ASC')
		->get();

		$chart_stacked = array();

		foreach($region_stats as $region) {

			$chart_stacked[$region->moon_region_name] = [
				'16650' => $region->dysprosium,
				'16651' => $region->neodymium,
				'16652' => $region->promethium,
				'16653' => $region->thulium,

				'16647' => $region->caesium,
				'16648' => $region->hafnium,
				'16646' => $region->mercury,
				'16649' => $region->technetium,

				'16643' => $region->cadmium,
				'16641' => $region->chromium,
				'16644' => $region->platinum,
				'16642' => $region->vanadium,

				'16640' => $region->cobalt,
				'16639' => $region->scandium,
				'16638' => $region->titanium,
				'16637' => $region->tungsten,

				'16634' => $region->atmo_gases,
				'16635' => $region->eva_depo,
				'16633' => $region->hydrocarbons,
				'16636' => $region->silicates,

			];
		}

		return view('moons.regional_report', compact('region_stats', 'chart_stacked'));

	}

	public function regional_old_report() {

		$region_stats = DB::table('moons')
		->select(
			DB::raw('moon_region_name as moon_region_name'),
			DB::raw('moon_region_id as moon_region_id'),
			DB::raw('sum(moon_value_24_hour) as regional_value_24_hour'),
			DB::raw('sum(moon_value_7_day) as regional_value_7_day'),
			DB::raw('sum(moon_value_30_day) as regional_value_30_day'),
			DB::raw('COUNT( (CASE WHEN moon_region_id > 1 THEN moon_region_id END) ) AS total_moons'),
			DB::raw('COUNT( (CASE WHEN moon_value_24_hour > 1 THEN moon_value_24_hour END) ) AS scanned_moons'),
			DB::raw('COUNT( (CASE WHEN moon_r_rating = 64 THEN moon_r_rating END) ) AS r64'),
			DB::raw('COUNT( (CASE WHEN moon_r_rating = 32 THEN moon_r_rating END) ) AS r32'),
			DB::raw('COUNT( (CASE WHEN moon_r_rating = 16 THEN moon_r_rating END) ) AS r16'),
			DB::raw('COUNT( (CASE WHEN moon_r_rating = 8 THEN moon_r_rating END) ) AS r8'),
			DB::raw('COUNT( (CASE WHEN moon_r_rating = 4 THEN moon_r_rating END) ) AS r4'),
			DB::raw('COUNT( (CASE WHEN moon_atmo_gases = 1 THEN moon_atmo_gases END) ) AS atmo_gases'),
			DB::raw('COUNT( (CASE WHEN moon_cadmium = 1 THEN moon_cadmium END) ) AS cadmium'),
			DB::raw('COUNT( (CASE WHEN moon_caesium = 1 THEN moon_caesium END) ) AS caesium'),
			DB::raw('COUNT( (CASE WHEN moon_chromium = 1 THEN moon_chromium END) ) AS chromium'),
			DB::raw('COUNT( (CASE WHEN moon_cobalt = 1 THEN moon_cobalt END) ) AS cobalt'),
			DB::raw('COUNT( (CASE WHEN moon_dysprosium = 1 THEN moon_dysprosium END) ) AS dysprosium'),
			DB::raw('COUNT( (CASE WHEN moon_eva_depo = 1 THEN moon_eva_depo END) ) AS eva_depo'),
			DB::raw('COUNT( (CASE WHEN moon_hafnium = 1 THEN moon_hafnium END) ) AS hafnium'),
			DB::raw('COUNT( (CASE WHEN moon_hydrocarbons = 1 THEN moon_hydrocarbons END) ) AS hydrocarbons'),
			DB::raw('COUNT( (CASE WHEN moon_mercury = 1 THEN moon_mercury END) ) AS mercury'),
			DB::raw('COUNT( (CASE WHEN moon_neodymium = 1 THEN moon_neodymium END) ) AS neodymium'),
			DB::raw('COUNT( (CASE WHEN moon_platinum = 1 THEN moon_platinum END) ) AS platinum'),
			DB::raw('COUNT( (CASE WHEN moon_promethium = 1 THEN moon_promethium END) ) AS promethium'),
			DB::raw('COUNT( (CASE WHEN moon_scandium = 1 THEN moon_scandium END) ) AS scandium'),
			DB::raw('COUNT( (CASE WHEN moon_silicates = 1 THEN moon_silicates END) ) AS silicates'),
			DB::raw('COUNT( (CASE WHEN moon_technetium = 1 THEN moon_technetium END) ) AS technetium'),
			DB::raw('COUNT( (CASE WHEN moon_thulium = 1 THEN moon_thulium END) ) AS thulium'),
			DB::raw('COUNT( (CASE WHEN moon_titanium = 1 THEN moon_titanium END) ) AS titanium'),
			DB::raw('COUNT( (CASE WHEN moon_tungsten = 1 THEN moon_tungsten END) ) AS tungsten'),
			DB::raw('COUNT( (CASE WHEN moon_vanadium = 1 THEN moon_vanadium END) ) AS vanadium')
		)
		->groupBy('moon_region_name')
		->orderBy('moon_region_name', 'ASC')
		->get();

		$chart_stacked = array();

		foreach($region_stats as $region) {

			$chart_stacked[$region->moon_region_name] = [
				'16650' => $region->dysprosium,
				'16651' => $region->neodymium,
				'16652' => $region->promethium,
				'16653' => $region->thulium,

				'16647' => $region->caesium,
				'16648' => $region->hafnium,
				'16646' => $region->mercury,
				'16649' => $region->technetium,

				'16643' => $region->cadmium,
				'16641' => $region->chromium,
				'16644' => $region->platinum,
				'16642' => $region->vanadium,

				'16640' => $region->cobalt,
				'16639' => $region->scandium,
				'16638' => $region->titanium,
				'16637' => $region->tungsten,

				'16634' => $region->atmo_gases,
				'16635' => $region->eva_depo,
				'16633' => $region->hydrocarbons,
				'16636' => $region->silicates,

			];
		}

		return view('moons.regional_old_report', compact('region_stats', 'chart_stacked'));

	}



	public function regions() {

		$regions = NewMoons::
		orderBy('moon_region_name', 'ASC')
		->groupBy('moon_region_name')
		->get();

		return view('moons.regions', compact('regions'));

	}

	public function systems($region_id) {

		$systems = NewMoons::
		orderBy('moon_name', 'ASC')
		->where('moon_region_id', $region_id)
		->groupBy('moon_system_name')
		->paginate(50);

		$region = NewMoons::
		orderBy('moon_system_name', 'ASC')
		->where('moon_region_id', $region_id)
		->first();

		$moons = NewMoons::
		where('moon_region_id', $region_id)
		->get();

		$moon_scans = MoonScans::
		groupBy('moon_id')
		->get();



		return view('moons.systems', compact('systems', 'region', 'moon', 'moon_scans', 'moons'));

	}

	public function system($system_id) {

		$system = NewMoons::
		orderBy('moon_system_name', 'ASC')
		->where('moon_system_id', $system_id)
		->first();

		$moons = NewMoons::
		where('moon_system_id', $system_id)
		->paginate(100);

		$scanned_moons = MoonScans::
		groupBy('moon_id')
		->get();

		return view('moons.system', compact('moons', 'system', 'scanned_moons'));

	}


	public function constellations($constellation_id) {

		$constellations = NewMoons::
		orderBy('moon_name', 'ASC')
		->where('moon_constellation_id', $constellation_id)
		->paginate(100);

		$constellation = NewMoons::
		orderBy('moon_system_name', 'ASC')
		->where('moon_constellation_id', $constellation_id)
		->first();

		return view('moons.constellations', compact('systems', 'constellation'));

	}

	/*
	 * EVE Mining Ledger
	 *
	 * Developed by scopehone <scopeh@gmail.com>
	 * In conjuction with Mindstar Technology 
	 *
	 */
	public function dscan(Request $request) 
	{

		/* Raw Data 
		Moon	Moon Product	Quantity	Ore TypeID	SolarSystemID	PlanetID	MoonID
		1DQ1-A III - Moon 1
		Bitumens	0.186903074384	45492	30004759	40301374	40301375
		Cubic Bistot	0.183727398515	46676	30004759	40301374	40301375
		Opulent Pyroxeres	0.31917026639	46686	30004759	40301374	40301375
		Sparkling Plagioclase	0.310199260712	46685	30004759	40301374	40301375
		*/

		// Declare Array for Populating in Foreach
		$goo = [];

		// Assign Input to Variable.
		$dscan = $request->input('dscan');

		if($request['dscan'] == null) {
			return redirect()->back()
			->withErrors('Fill the box you dick.'); 
		}

		/* Request Data
		"""
		Moon\tMoon Product\tQuantity\tOre TypeID\tSolarSystemID\tPlanetID\tMoonID\r\n
		1DQ1-A III - Moon 1\r\n
		\tBitumens\t0.186903074384\t45492\t30004759\t40301374\t40301375\r\n
		\tCubic Bistot\t0.183727398515\t46676\t30004759\t40301374\t40301375\r\n
		\tOpulent Pyroxeres\t0.31917026639\t46686\t30004759\t40301374\t40301375\r\n
		\tSparkling Plagioclase\t0.310199260712\t46685\t30004759\t40301374\t40301375
		"""
		*/

		// Explode by new line and convert to scan to an array.
		$line = explode("\n", $dscan);

		/* Exploded Data
		array:6 [▼
		  0 => "Moon\tMoon Product\tQuantity\tOre TypeID\tSolarSystemID\tPlanetID\tMoonID\r"
		  1 => "1DQ1-A III - Moon 1\r"
		  2 => "\tBitumens\t0.186903074384\t45492\t30004759\t40301374\t40301375\r"
		  3 => "\tCubic Bistot\t0.183727398515\t46676\t30004759\t40301374\t40301375\r"
		  4 => "\tOpulent Pyroxeres\t0.31917026639\t46686\t30004759\t40301374\t40301375\r"
		  5 => "\tSparkling Plagioclase\t0.310199260712\t46685\t30004759\t40301374\t40301375"
		]
		*/

		// Bring the array index in to remove any crap. i.e column headers/system name/moon name. 
		// We don't care about this, because CCP has given us the ID's and we can look up our own database for this information.
		// Go through each line of the array and grab the moon data to populate new array.

		// Found String "Moon", Ignore it and move onto the juicy data.


		$regex = "/Moon/";	
		$cleaned_parse = preg_grep($regex, $line, PREG_GREP_INVERT);


		foreach ($cleaned_parse as $key => $moon) {

			/* Parsed String for Filtering 
			"Moon\tMoon Product\tQuantity\tOre TypeID\tSolarSystemID\tPlanetID\tMoonID\r"
			*/

			// Define Search String to remove garbage.

			// Now that we have removed the column titles and garbage, lets parse the data and build a new array for storing in the database.

			/* Raw Data
			* "\tBitumens\t0.186903074384\t45492\t30004759\t40301374\t40301375\r"
			*/

			// Lets clean up that data so we can work with it.
			// Trim each side \t and \r

			// Removed added to Job $sanitised = rtrim($moon, "\r");

			/* Raw Data
			* "\tBitumens\t0.186903074384\t45492\t30004759\t40301374\t40301375"
			*/

			// Removed added to Job $parsed = ltrim($sanitised, "\t");

			/* Raw Data
			* "Bitumens\t0.186903074384\t45492\t30004759\t40301374\t40301375"
			*/

			// Explode by /t (tab) to get an array for each line so we can do something with it.
			// We make use of list() which gives us nice variables rather than an index array to work it. Alot cleaner and easier to read.

			// Removed add to Job list($product, $quantity, $ore_type_id, $solar_system_id, $planet_id, $moon_id) = explode("\t", $parsed);

			//$ore = explode("\t", $parsed);
			// We are going to populate our array we declared on line 33.
			// Lets give it come index's too so we know what we are looking at.

			// Check if it already exists


			//$moonDetails = $this->getMoonDetails($moon_id);
			//$systemDetails = $this->getSystemDetails($solar_system_id);
			/*
			$insert = MoonScans::updateOrCreate([
				'moon_hash' 		=> md5($moon_id . $product . $solar_system_id),
			],[
				'moon_id' 			=> $moon_id,
				//'moon_name' 		=> $moonDetails->name,
				//'moon_system_id' 	=> $moonDetails->system_id,
				//'moon_system_name' 	=> $systemDetails->name,
				'moon_product'		=> $product,
				'moon_quantity'		=> $quantity,
				'moon_ore_type_id'	=> $ore_type_id,				
			]);
			*/

			$this->dispatch(new parseMoonData($moon));

			// End of Foreach 

		}

		return redirect()->back()
		->withSuccess('Moons Added');

	}

	public function adash_import(Request $request) 
	{

		/* Raw Data 
		Moon	Moon Product	Quantity	Ore TypeID	SolarSystemID	PlanetID	MoonID
		1DQ1-A III - Moon 1
		Bitumens	0.186903074384	45492	30004759	40301374	40301375
		Cubic Bistot	0.183727398515	46676	30004759	40301374	40301375
		Opulent Pyroxeres	0.31917026639	46686	30004759	40301374	40301375
		Sparkling Plagioclase	0.310199260712	46685	30004759	40301374	40301375

		Region	System	Planet	Moon	Mineral	Amount	Rarity	MoonScanned	MoonScanner	Character	Short	MoonConflicted
		Aridia	Van	Van III	Van III - Moon 1	Bitumens	0.546501994133	4	2017-10-25 00:43	Johnny Williams, very high (ls)	Johnny Williams	CONDI	
		Aridia	Van	Van III	Van III - Moon 1	Glossy Scordite	0.190873682499	0	2017-10-25 00:43	Johnny Williams, very high (ls)	Johnny Williams	CONDI	
		Aridia	Van	Van III	Van III - Moon 1	Scintillating Hemorphite	0.262624323368	0	2017-10-25 00:43	Johnny Williams, very high (ls)	Johnny Williams	CONDI	
		Aridia	Van	Van III	Van III - Moon 2	Bitumens	0.351685494184	4	2017-10-25 00:43	Johnny Williams, very high (ls)	Johnny Williams	CONDI	
		Aridia	Van	Van III	Van III - Moon 2	Pellucid Crokite	0.192732647061	0	2017-10-25 00:43	Johnny Williams, very high (ls)	Johnny Williams	CONDI	
		Aridia	Van	Van III	Van III - Moon 2	Sparkling Plagioclase	0.277161687613	0	2017-10-25 00:43	Johnny Williams, very high (ls)	Johnny Williams	CONDI	
		Aridia	Van	Van III	Van III - Moon 2	Zeolites	0.178420171142	4	2017-10-25 00:43	Johnny Williams, very high (ls)	Johnny Williams	CONDI	
		*/

		// Declare Array for Populating in Foreach
		$goo = [];

		// Assign Input to Variable.
		$adash_import = $request->input('adash_import');

		if($request['adash_import'] == null) {
			return redirect()->back()
			->withErrors('Fill the box you dick.'); 
		}

		$line = explode("\n", $adash_import);

		$regex = "/Region/";	
		$cleaned_parse = preg_grep($regex, $line, PREG_GREP_INVERT);

		foreach ($cleaned_parse as $key => $moon) {

			$this->dispatch(new parseADASHData($moon));

		}



		

		return redirect()->back()
		->withSuccess('Moons added to the Queue.');

	}




	public function getMoonDetails($moon) { 

		$configuration = Configuration::getInstance();

		$client_id = config('eve.client_id');
		$secret_key = config('eve.secret_key');

		$esi = new Eseye();

		try { 

			$response = $esi->invoke('get', '/universe/moons/{moon_id}/', [   
				'moon_id' => $moon,
			]);

			/*
			{
			  "moon_id": 40301375,
			  "name": "1DQ1-A III - Moon 1",
			  "position": {
			    "x": -100147692920,
			    "y": 903576951,
			    "z": 89687575373
			  },
			  "system_id": 30004759
			}
			*/

			return $response;

		} catch (EsiScopeAccessDeniedException $e) {

			//return redirect()->withErrors('SSO Token is invalid');

		} catch (RequestFailedException $e) {

			//return redirect()->withErrors('Got an ESI Error');

		} catch (Exception $e) {

			//return redirect()->withErrors('ESI is fucked');
		}


		//$this->info('Complete');

	}


	public function getSystemDetails($system) { 

		$configuration = Configuration::getInstance();

		$client_id = config('eve.client_id');
		$secret_key = config('eve.secret_key');

		$esi = new Eseye();

		try { 

			$response = $esi->invoke('get', '/universe/systems/{system_id}/', [   
				'system_id' => $system,
			]);

			/*
			{
			  "moon_id": 40301375,
			  "name": "1DQ1-A III - Moon 1",
			  "position": {
			    "x": -100147692920,
			    "y": 903576951,
			    "z": 89687575373
			  },
			  "system_id": 30004759
			}
			*/

			return $response;

		} catch (EsiScopeAccessDeniedException $e) {

			//return redirect()->withErrors('SSO Token is invalid');

		} catch (RequestFailedException $e) {

			//return redirect()->withErrors('Got an ESI Error');

		} catch (Exception $e) {

			//return redirect()->withErrors('ESI is fucked');
		}


		//$this->info('Complete');

	}



}
