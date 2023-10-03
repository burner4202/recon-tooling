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

use Auth;
use Vanguard\Ledger;
use Vanguard\Characters;
use Vanguard\Corporations;
use Vanguard\SolarSystems;
use Vanguard\HarvestedMaterials;
use Vanguard\RefinedMaterials;


class EVEHelperController extends Controller
{
    /**
     * Return Solar System Details
     *
     * @return Response
    */
    public function solarSystem($id)
    {
    	$solarSystem = SolarSystems::where('system_id', $id)
    	->first();

    	return $solarSystem;

    }

    /**
     * Return Ore Details
     *
     * @return Response
    */
    public function Ore($id)
    {
    	$ore = HarvestedMaterials::where('type_id', $id)
    	->first();

    	return $ore;

    }

    /**
     * Return Character Details
     *
     * @return Response
    */
    public function Character($id)
    {
    	$character = Characters::where('character_id', $id)
    	->first();

    	return $character;

    }

    /**
     * Return Corporation Details
     *
     * @return Response
    */
    public function Corporation($id)
    {
    	$corporation = Corporations::where('corporation_id', $id)
    	->first();

    	return $corporation;

    }
}
