<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class CharacterRelationship extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */

    use Sortable;

    protected $table = 'character_relationship';

    protected $fillable = ['character_id', 'character_name', 'associated_character_id', 'associated_character_name', 'associated_corporation_id', 'associated_corporation_name', 'associated_alliance_id', 'associated_alliance_name', 'notes', 'added_by']; public $sortable = ['character_id', 'character_name', 'associated_character_id', 'associated_character_name', 'associated_corporation_id', 'associated_corporation_name', 'associated_alliance_id', 'associated_alliance_name', 'notes', 'added_by', 'created_at', 'updated_at'];

}

