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
use Kyslik\ColumnSortable\Sortable;

class Fittings extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
    */

    use Sortable;
    protected $table = 'fittings';

    protected $fillable = [
    	'fitting_name',
    	'fitting_hull_name',
    	'fitting_hull_type_id',
    	'fitting_hull_value',
    	'fitting_modules',
    	'fitting_module_value',
    	'fitting_cargo',
    	'fitting_cargo_value',
    	'fitting_value',
    	'fitting_fitting_type',
    ];

    public $sortable = [
    	'fitting_name',
    	'fitting_hull_name',
    	'fitting_hull_type_id',
    	'fitting_hull_value',
    	'fitting_modules',
    	'fitting_module_value',
    	'fitting_cargo',
    	'fitting_cargo_value',
    	'fitting_value',
    	'fitting_fitting_type',
    ];
}







