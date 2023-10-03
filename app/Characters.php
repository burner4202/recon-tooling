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

class Characters extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */

    use Sortable;
    protected $table = 'characters';

    protected $fillable = ['character_character_id', 'character_name', 'character_birthday', 'character_corporation_id', 'character_security_status', 'titan', 'faction_titan', 'super', 'faction_super', 'carrier', 'fax', 'dread', 'faction_dread', 'character_corporation_name', 'character_alliance_id', 'character_alliance_name', 'monitor', 'jump_freighter', 'cyno', 'industrial_cyno', 'freighter', 'rorqual'];

    protected $sortable = ['character_character_id', 'character_name', 'character_birthday', 'character_corporation_id', 'character_security_status', 'titan', 'faction_titan', 'super', 'faction_super', 'carrier', 'fax', 'dread', 'faction_dread', 'character_corporation_name', 'character_alliance_id', 'character_alliance_name', 'monitor', 'jump_freighter', 'cyno', 'industrial_cyno', 'freighter', 'rorqual'];

    
}

