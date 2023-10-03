<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Stagers extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */

    use Sortable;
    protected $table = 'stagers';

    protected $fillable = ['solar_system_id', 'solar_system_name', 'constellation_id', 'constellation_name', 'region_id', 'region_name', 'alliance_id', 'alliance_name', 'created_by_user_id', 'created_by_user_username'];

    public $sortable = ['solar_system_id', 'solar_system_name', 'constellation_id', 'constellation_name', 'region_id', 'region_name', 'alliance_id', 'alliance_name', 'created_by_user_id', 'created_by_user_username'];
}

