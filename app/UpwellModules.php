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

class UpwellModules extends Model
{
       /**
     * The database table used by the model.
     *
     * @var string
     */

    use Sortable;
    protected $table = 'upwell_modules';

    protected $fillable = ['upm_type_id', 'upm_name', 'upm_industry', 'upm_description', 'upm_dogma_attributes', 'upm_dogma_effects', 'group_id', 'market_group_id', 'slot'];

    public $sortable = ['upm_type_id', 'upm_name', 'upm_industry', 'upm_description', 'upm_dogma_attributes', 'upm_dogma_effects', 'group_id', 'market_group_id', 'slot'];
}
