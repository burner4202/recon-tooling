<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class UpwellRigs extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */

    use Sortable;
    protected $table = 'upwell_rigs';

    protected $fillable = ['type_id', 'name', 'description', 'group_id', 'icon_id', 'meta_data', 'value', 'item_prices', 'sum_prices'];

    protected $sortable = ['type_id', 'name', 'description', 'group_id', 'icon_id', 'meta_data', 'value', 'item_prices', 'sum_prices'];
}
