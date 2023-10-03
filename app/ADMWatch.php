<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class ADMWatch extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */

    use Sortable;

    protected $table = 'adm_watch';

    protected $fillable = [
    	'adm_system_id',
    	'adm_system_name',
    	'adm_constellation_id',
    	'adm_constellation_name',
    	'adm_region_id',
    	'adm_region_name'
    ];

    public $sortable = [
    	'adm_system_id',
    	'adm_system_name',
    	'adm_constellation_id',
    	'adm_constellation_name',
    	'adm_region_id',
    	'adm_region_name'
    ];
}


