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


class Alliances extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */

    use Sortable;

    protected $table = 'alliances';

    protected $fillable = ['alliance_alliance_id', 'alliance_creator_corporation_id', 'alliance_creator_id', 'alliance_date_founded', 'alliance_executor_corporation_id', 'alliance_name', 'alliance_ticker'];

    public $sortable = ['alliance_name', 'alliance_ticker', 'created_at', 'updated_at'];

    public function corporations() 
    {
    	return $this->hasMany('Vanguard\Corporations');
    }

        public function structures() 
    {
    	return $this->hasMany('Vanguard\KnownStructures');
    }

}


