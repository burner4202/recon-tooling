<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Salvage extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */

    use Sortable;
    protected $table = 'salvage_materials';

    protected $fillable = ['type_id', 'name', 'description', 'group_id', 'icon_id', 'volume'];
}
