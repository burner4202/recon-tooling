<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class AugswarmTracking extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */

    use Sortable;

    protected $table = 'augswarm_tracking';

    protected $fillable = [
        'at_character_id',
        'at_solar_system_id',
        'at_last_login',
        'at_last_logout',
        'at_logins',
        'at_online',
        'at_ship_name',
        'at_ship_type_id',
        'at_ship_type_id_name',
        'at_last_updated'
    ];

    public $sortable = [ 
        'at_character_id',
        'at_solar_system_id',
        'at_last_login',
        'at_last_logout',
        'at_logins',
        'at_online',
        'at_ship_name',
        'at_ship_type_id',
        'at_ship_type_id_name',
        'at_last_updated'
    ];
}
