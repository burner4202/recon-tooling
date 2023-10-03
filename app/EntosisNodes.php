<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;


class EntosisNodes extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */

    use Sortable;
    protected $table = 'entosis_nodes';

    protected $fillable = [
    	'en_campaign_id',
    	'eh_target_system',
    	'en_node_id',
    	'en_added_by_user_id',
    	'en_added_by_username',
    	'en_allocated_character_id',
    	'en_allocated_character_name',
    	'en_node_system_id',
    	'en_node_system_name',
    	'eh_registered_at',
    	'en_est_completed',
    	'en_node_status',
    	'en_completed_at',
    	'updated_at',
    	'created_at'
    ];
}

	