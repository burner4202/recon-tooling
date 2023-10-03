<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class MoonGoo extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */

    use Sortable;

        protected $table = 'moon_goo';

        protected $fillable = ['name', 'type_id', 'description', 'group_id', 'icon_id', 'portion_size'];
}
