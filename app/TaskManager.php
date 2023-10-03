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

class TaskManager extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
    */

    use Sortable;
    protected $table = 'task_manager';

    protected $fillable = ['tm_created_by_user_id', 'tm_created_by_user_username', 'tm_solar_system_id', 'tm_solar_system_name', 'tm_constellation_id', 'tm_constellation_name', 'tm_region_id', 'tm_region_name', 'tm_task', 'tm_prority', 'tm_notes', 'tm_created_datetime_at', 'tm_state', 'tm_accepted_by_user_id', 'tm_accepted_by_user_username', 'tm_accepted_datetime_at', 'tm_completed_datetime_at'];

    public $sortable = ['tm_created_by_user_id', 'tm_created_by_user_username', 'tm_solar_system_id', 'tm_solar_system_name', 'tm_constellation_id', 'tm_constellation_name', 'tm_region_id', 'tm_region_name', 'tm_task', 'tm_prority', 'tm_notes', 'tm_created_datetime_at', 'tm_state', 'tm_accepted_by_user_id', 'tm_accepted_by_user_username', 'tm_accepted_datetime_at', 'tm_completed_datetime_at'];
}
