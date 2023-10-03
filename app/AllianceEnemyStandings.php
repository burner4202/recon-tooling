<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class AllianceEnemyStandings extends Model
{
	use Sortable;

	protected $table = 'alliance_enemy_standings';

	protected $fillable = ['as_contact_id', 'as_enemy_alliance_id', 'as_enemy_alliance_name', 'as_contact_type', 'as_standing', 'as_character_name', 'as_corporation_id', 'as_corporation_name', 'as_alliance_id', 'as_alliance_name'];

	public $sortable = ['as_contact_id', 'as_enemy_alliance_id', 'as_enemy_alliance_name', 'as_contact_type', 'as_standing', 'as_character_name', 'as_corporation_id', 'as_corporation_name', 'as_alliance_id', 'as_alliance_name', 'created_at', 'updated_at'];
}
