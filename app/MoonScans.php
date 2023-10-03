<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class MoonScans extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */

    use Sortable;
    protected $table = 'moon_scans';

    protected $fillable = ['moon_hash', 'moon_id', 'moon_name', 'moon_system_id', 'moon_system_name', 'moon_product', 'moon_quantity', 'moon_ore_type_id'];
}
