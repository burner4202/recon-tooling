<?php

/*
 * Goonswarm Federation Recon Tools
 *
 * Developed by scopehone <scopeh@gmail.com>
 * In conjuction with Natalya Spaghet & Mindstar Technology 
 *
 */

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Constellations extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'constellations';

    protected $fillable = ['con_constellation_id', 'con_constellation_name', 'con_position', 'con_region_id', 'con_region_name', 'con_systems'];
}
