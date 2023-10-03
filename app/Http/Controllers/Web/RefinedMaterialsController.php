<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

use Auth;
use Carbon\Carbon;
use Vanguard\RefinedMaterials;
use Vanguard\MarketPrices;

class RefinedMaterialsController extends Controller
{
     /**
     * HarvestedController constructor.
     */
     public function __construct()
     {
     	$this->middleware('auth');

     }

    /**
     * Display a listing of the resource. (Standard Ore)
     *
     * @return Response
    */
    public function minerals()
    {
    	$minerals = RefinedMaterials::where('group_id', '=', '18')
    	->orderBy('name', 'asc')
    	->get();

    	return view('refined.minerals', compact('minerals'));

    }

    public function mineralsHistory($type_id)
    {
    	$historyHighest = array();
    	$historyAverage = array();
    	$historyLowest = array();
        $historyVolume = array();

        $mineralInformation = RefinedMaterials::where('type_id', $type_id)
        ->first();

        $to = Carbon::today()->format('Y-m-d');   

        $from = Carbon::today()->subMonth(6)->format('Y-m-d');  

        $mineralHistory = MarketPrices::where('type_id', $type_id) 
        ->whereBetween('date', [$from, $to])
    	//->orderBy('date', 'ASC')
        ->get();

        foreach($mineralHistory as $value) {

          $historyLowest[$value->date] = $value->lowest;

      }

      foreach($mineralHistory as $value) {

          $historyAverage[$value->date] = $value->average;

      }

      foreach($mineralHistory as $value) {

          $historyHighest[$value->date] = $value->highest;

      }


      foreach($mineralHistory as $value) {

        $historyVolume[$value->date] = $value->volume;

    }


    return view('refined.minerals_history', compact('historyLowest', 'historyHighest', 'historyAverage', 'historyVolume', 'mineralInformation'));

}


    /**
     * Display a listing of the resource. (Standard Ore)
     *
     * @return Response
    */
    public function ice()
    {
    	$ices = RefinedMaterials::where('group_id', '=', '423')
    	->orderBy('name', 'asc')
    	->get();

    	return view('refined.ice', compact('ices'));

    }

    public function iceHistory($type_id)
    {
    	$historyHighest = array();
    	$historyAverage = array();
    	$historyLowest = array();

    	$mineralInformation = RefinedMaterials::where('type_id', $type_id)
    	->first();

    	$to = Carbon::today()->format('Y-m-d');   

    	$from = Carbon::today()->subMonth(6)->format('Y-m-d');  

    	$mineralHistory = MarketPrices::where('type_id', $type_id) 
    	->whereBetween('date', [$from, $to])
    	//->orderBy('date', 'ASC')
    	->get();

    	foreach($mineralHistory as $value) {

    		$historyLowest[$value->date] = $value->lowest;

    	}

    	foreach($mineralHistory as $value) {

    		$historyAverage[$value->date] = $value->average;

    	}

    	foreach($mineralHistory as $value) {

    		$historyHighest[$value->date] = $value->highest;

    	}


    	return view('refined.ice_history', compact('historyLowest', 'historyHighest', 'historyAverage', 'mineralInformation'));

    }

    /**
     * Display a listing of the resource. (Standard Ore)
     *
     * @return Response
    */
    public function moons()
    {
    	$moons = RefinedMaterials::where('group_id', '=', '427')
    	->orderBy('name', 'asc')
    	->get();

    	return view('refined.moons', compact('moons'));

    }

    public function moonsHistory($type_id)
    {
    	$historyHighest = array();
    	$historyAverage = array();
    	$historyLowest = array();

    	$mineralInformation = RefinedMaterials::where('type_id', $type_id)
    	->first();

    	$to = Carbon::today()->format('Y-m-d');   

    	$from = Carbon::today()->subMonth(6)->format('Y-m-d');  

    	$mineralHistory = MarketPrices::where('type_id', $type_id) 
    	->whereBetween('date', [$from, $to])
    	//->orderBy('date', 'ASC')
    	->get();

    	foreach($mineralHistory as $value) {

    		$historyLowest[$value->date] = $value->lowest;

    	}

    	foreach($mineralHistory as $value) {

    		$historyAverage[$value->date] = $value->average;

    	}

    	foreach($mineralHistory as $value) {

    		$historyHighest[$value->date] = $value->highest;

    	}

        foreach($mineralHistory as $value) {

            $historyVolume[$value->date] = $value->volume;

        }


        return view('refined.moons_history', compact('historyLowest', 'historyHighest', 'historyAverage', 'historyVolume', 'mineralInformation'));

    }

    /**
     * Display Price
     *
     * @return Response
    */
    public function getPrice($type_id)
    {
    	$price = MarketPrices::where('type_id', $type_id)
    	->orderBy('date', 'desc')
    	->first();

    	return $price;

    }


    public function r64_moons()
    {

    	$promethium = array();
    	$dysprosium = array();
    	$neodymium = array();
    	$thulium = array();

    	$to = Carbon::today()->format('Y-m-d');   

    	$from = Carbon::today()->subMonths(6)->format('Y-m-d');  

    	$gooHistory = MarketPrices::whereBetween('date', [$from, $to])
    	//->orderBy('date', 'ASC')
    	->get();

    	foreach($gooHistory as $value) {

    		if($value->type_id == 16652) {
    			$promethium[Carbon::parse($value->date)->format('d-M-Y')] = $value->highest;
    		}

    		if($value->type_id == 16650) {
    			$dysprosium[Carbon::parse($value->date)->format('d-M-Y')] = $value->highest;
    		}

    		if($value->type_id == 16651) {
    			$neodymium[Carbon::parse($value->date)->format('d-M-Y')] = $value->highest;
    		}

    		if($value->type_id == 16653) {
    			$thulium[Carbon::parse($value->date)->format('d-M-Y')] = $value->highest;
    		}

    	}


    	return view('goo.r64', compact(
    		'promethium',
    		'dysprosium',
    		'neodymium',
    		'thulium'
    	));

    }
}
