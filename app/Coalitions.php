<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Coalitions extends Model
{
       /**
     * The database table used by the model.
     *
     * @var string
     */

    use Sortable;

    protected $table = 'coalitions';

    protected $fillable = ['name', 'no_of_alliances', 'member_count', 'added_by'];

    public $sortable = ['name', 'no_of_alliances', 'member_count', 'added_by'];
}
