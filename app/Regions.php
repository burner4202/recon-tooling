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

class Regions extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'regions';

    protected $fillable = ['reg_region_id', 'reg_region_name', 'reg_description', 'reg_constellations'];
}
