<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class ActivityTracker extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */

    use Sortable;

    protected $table = 'activity_tracker';

    protected $fillable = ['at_user_id', 'at_username', 'at_action', 'at_structure_id', 'at_structure_name', 'at_structure_hash', 'at_system_id', 'at_system_name', 'at_corporation_id', 'at_corporation_name', 'at_action' ];

    public $sortable = ['at_user_id', 'at_username', 'at_action', 'at_structure_id', 'at_structure_name', 'at_structure_hash', 'at_system_id', 'at_system_name', 'at_corporation_id', 'at_corporation_name', 'at_action'];

}



