<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
 use Kyslik\ColumnSortable\Sortable;

class CharacterReport extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */

    use Sortable;

    protected $table = 'character_reporting';

    protected $fillable = ['character_id', 'character_name', 'corporation_id', 'corporation_name', 'system_id', 'system_name', 'constellation_id', 'constellation_name', 'region_id', 'region_name', 'alliance_id', 'alliance_name', 'hull_type', 'notes'];

    public $sortable = ['character_id', 'character_name', 'corporation_id', 'corporation_name', 'system_id', 'system_name', 'constellation_id', 'constellation_name', 'region_id', 'region_name', 'alliance_id', 'alliance_name', 'hull_type', 'created_at', 'updated_at', 'notes'];
}
