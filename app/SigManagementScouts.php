<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

use Kyslik\ColumnSortable\Sortable;

class SigManagementScouts extends Model
{
        /**
     * The database table used by the model.
     *
     * @var string
     */

        use Sortable;

        protected $table = 'sig_management_scouts';

        protected $fillable = ['user_id', 'name', 'check_in', 'active', 'registered_on_rt'];

        public $sortable = ['name', 'check_in', 'active', 'registered_on_rt'];
    }

