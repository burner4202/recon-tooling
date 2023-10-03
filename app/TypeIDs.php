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

class TypeIDs extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
    */

    use Sortable;
    
    protected $table = 'type_ids';

    protected $fillable = ['ti_type_id', 'ti_name', 'ti_description', 'ti_dogma_attributes', 'ti_dogma_effects', 'ti_group_id', 'ti_market_group_id', 'ti_slot'];

    public $sortable = ['ti_type_id', 'ti_name', 'ti_description', 'ti_dogma_attributes', 'ti_dogma_effects', 'ti_group_id', 'ti_market_group_id', 'ti_slot'];
}




