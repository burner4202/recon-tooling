<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class CoalitionsBake extends Model

{
    /**
     * The database table used by the model.
     *
     * @var string
     */

    use Sortable;

    protected $table = 'coalitions_alliances_bake';

    protected $fillable = ['coalition_id', 'coalition_name', 'corporation_id', 'corporation_name', 'corporation_member_count', 'alliance_id', 'alliance_name', 'alliance_ticker'];

    public $sortable = ['coalition_id', 'coalition_name', 'corporation_id', 'corporation_name', 'corporation_member_count', 'alliance_id', 'alliance_name', 'alliance_ticker'];
}


