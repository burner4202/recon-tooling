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

class Corporations extends Model 
{
    /**
     * The database table used by the model.
     *
     * @var string
     */

    use Sortable;

    protected $table = 'corporation';

    protected $fillable = ['corporation_corporation_id', 'corporation_alliance_id', 'corporation_creator_id', 'corporation_ceo_id', 'corporation_date_founded', 'corporation_description', 'corporation_member_count', 'corporation_name', 'corporation_tax_rate', 'corporation_ticker', 'corporation_url'];

    public $sortable = ['corporation_name', 'corporation_ticker', 'corporation_member_count', 'created_at', 'updated_at'];

    public function alliances() 
    {
    	return $this->belongsToMany('Vanguard\Alliances');
    }

    public function structures() 
    {
    	return $this->hasMany('Vanguard\KnownStructures');
    }
}

