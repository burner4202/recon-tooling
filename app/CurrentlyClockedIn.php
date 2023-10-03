<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class CurrentlyClockedIn extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */

    use Sortable;

    protected $table = 'currently_clocked_in';

    protected $fillable = [
    	'fleet_id', 
    	'fleet_owner',
    	'fleet_boss',
        'fleet_size',
    	'freemove',
        'advert',
    	'system_numbers',
    	'hull_numbers',
    	'active',
    	'created_at',
    	'updated_at'
    ];

    public $sortable = [
    	'fleet_id', 
    	'fleet_owner',
    	'fleet_boss',
        'fleet_size',
    	'freemove',
        'advert',
    	'system_numbers',
    	'hull_numbers',
    	'active',
    	'created_at',
    	'updated_at'
    ];
}
