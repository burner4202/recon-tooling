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

class ESITokens extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */

    use Sortable;
    protected $table = 'esi_tokens';

    protected $fillable = ['esi_user_id', 'esi_name', 'esi_character_id', 'esi_avatar', 'esi_token', 'esi_refresh_token', 'esi_scopes', 'esi_owner_hash', 'esi_active', 'esi_character_name', 'esi_corporation_id', 'esi_corporation_name', 'esi_online', 'esi_ship', 'esi_location'];

    public $sortable = ['esi_character_name', 'esi_corporation_name', 'esi_active', 'esi_online', 'esi_ship', 'esi_location'];
}


