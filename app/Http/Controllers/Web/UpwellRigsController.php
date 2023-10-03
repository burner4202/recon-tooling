<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Vanguard\Salvage;
use Vanguard\UpwellRigs;
use Vanguard\UpwellModules;
use Vanguard\MarketPrices;
use Carbon\Carbon;
use DB;

class UpwellRigsController extends Controller
{
	public function rigs() {

		$rigs = UpwellRigs::sortable()
		->orderBy('name', 'ASC')
		->paginate(100);

		$graph =$this->upwellChart();

		return view('upwell.rigs', compact('rigs', 'graph'));
	}

	public function rig($id) {

		$rig = UpwellRigs::where('type_id', $id)->first();

		$properties = json_decode($rig['meta_data']);
		$item_value = json_decode($rig['item_prices']);
		$item_sum = json_decode($rig['sum_prices']);

		$pie_chart  = array();
		$chartColours = array();

		$rig = UpwellRigs::where('type_id', $id)->first();
		$materials = $rig['sum_prices'];

		$prices = json_decode($materials, TRUE);

		foreach($prices as $id => $build) {

			$colour_one = rand(0,255);
			$colour_two = rand(0,255);
			$colour_three = rand(0,255);
			$transparency = 0.9;
			$colourScheme = "rgba(" . $colour_one . "," . $colour_two . "," . $colour_three . "," . $transparency .")";


			$salvage = $this->getSalvageDetails($id);

			$chartColours[$salvage->name] =  $colourScheme;
			$pie_chart[$salvage->name] = $build;
		}

		return view('upwell.rig', compact('rig', 'properties', 'item_value', 'item_sum', 'pie_chart', 'chartColours'));
	}

	/**
     * Display a listing of the resource. (Standard Ore)
     *
     * @return Response
    */
	public function getSalvageDetails($type_id)
	{
		$salvage = Salvage::where('type_id', $type_id)
		->first();

		return $salvage;

	}


	public function upwellChart() {

		$rig_metrics  = array();

		$rigs = DB::table('upwell_rigs')
		->select(DB::raw('name as name'),  DB::raw('sum(value) as value'))
		->orderBy('name')
		->groupBy('name')
		->get();

		foreach($rigs as $graph) {

			$rig_metrics[$graph->name] = $graph->value;
		}

		return $rig_metrics;

	}

	public function upwellChartManufacturePie($type_id) {

		$pie_chart  = array();
		$chartColours = array();

		$rig = UpwellRigs::where('type_id', $type_id)->first();
		$materials = $rig['sum_prices'];

		$prices = json_decode($materials, TRUE);

		foreach($prices as $id => $build) {

			$colour_one = rand(0,255);
			$colour_two = rand(0,255);
			$colour_three = rand(0,255);
			$transparency = 0.8;
			$colourScheme = "rgba(" . $colour_one . "," . $colour_two . "," . $colour_three . "," . $transparency .")";

			$totalMined[$ore->type_id_name] = $ore->total;
			$totalVolume[$ore->type_id_name] = $ore->quantity;
			$chartColours[$ore->type_id_name] =  $colourScheme;

			$salvage = $this->getSalvageDetails($id);

			$pie_chart[$salvage->name] = $build;
		}

		return $pie_chart;

	}

	public function modules() {

		$modules = UpwellModules::sortable()
		->orderBy('upm_name', 'ASC')
		->paginate(100);

		$date = Carbon::today()->subDay(2)->format('Y-m-d');  

		$prices = MarketPrices::
		where('date', $date)
		->groupBy('type_id')
		->get();

		return view('upwell.modules', compact('modules', 'prices'));
	}

	public function view_modules($type_id) {

		$historyHighest = array();
		$historyAverage = array();
		$historyLowest = array();
		$historyVolume = array();

		$moduleInformation = UpwellModules::where('upm_type_id', $type_id)
		->first();

		$to = Carbon::today()->format('Y-m-d');   

		$from = Carbon::today()->subMonth(6)->format('Y-m-d');  

		$moduleHistory = MarketPrices::where('type_id', $type_id) 
		->whereBetween('date', [$from, $to])
    	//->orderBy('date', 'ASC')
		->get();

		foreach($moduleHistory as $value) {

			$historyLowest[$value->date] = $value->lowest;

		}

		foreach($moduleHistory as $value) {

			$historyAverage[$value->date] = $value->average;

		}

		foreach($moduleHistory as $value) {

			$historyHighest[$value->date] = $value->highest;

		}

		foreach($moduleHistory as $value) {

			$historyVolume[$value->date] = $value->volume;

		}


		return view('upwell.view_module', compact('historyLowest', 'historyHighest', 'historyAverage', 'historyVolume', 'moduleInformation'));
		
	}

	public function salvage() {

		$salvage = Salvage::sortable()
		->orderBy('name', 'ASC')
		->paginate(100);

		$date = Carbon::today()->subDay(2)->format('Y-m-d');  

		$prices = MarketPrices::
		where('date', $date)
		->groupBy('type_id')
		->get();

		return view('upwell.salvage', compact('salvage', 'prices'));
	}

	public function view_salvage($type_id)
	{
		$historyHighest = array();
		$historyAverage = array();
		$historyLowest = array();
		$historyVolume = array();

		$salvageInformation = Salvage::where('type_id', $type_id)
		->first();

		$to = Carbon::today()->format('Y-m-d');   

		$from = Carbon::today()->subMonth(6)->format('Y-m-d');  

		$salvageHistory = MarketPrices::where('type_id', $type_id) 
		->whereBetween('date', [$from, $to])
    	//->orderBy('date', 'ASC')
		->get();

		foreach($salvageHistory as $value) {

			$historyLowest[$value->date] = $value->lowest;

		}

		foreach($salvageHistory as $value) {

			$historyAverage[$value->date] = $value->average;

		}

		foreach($salvageHistory as $value) {

			$historyHighest[$value->date] = $value->highest;

		}

		foreach($salvageHistory as $value) {

			$historyVolume[$value->date] = $value->volume;

		}


		return view('upwell.view_salvage', compact('historyLowest', 'historyHighest', 'historyAverage', 'historyVolume', 'salvageInformation'));

	}
}
