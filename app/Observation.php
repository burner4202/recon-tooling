<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Observation extends Model
{
	use Sortable;

	/**
     * The database table used by the model.
     *
     * @var string
     */

	protected $table = 'observations';

	protected $fillable = [
		'unique_id',
		'observation',
		'created_by_user_id',
		'created_by_username',
		'state',
		'prority',
		'tags',
		'solar_system_id',
		'solar_system_name',
		'corporation_id',
		'corporation_name',
		'corporation_ticker',
		'alliance_id',
		'alliance_name',
		'alliance_ticker',
		'score',
		'reviewed_by_user_id',
		'reviewed_by_username'
	];

	protected $sortable = [
		'unique_id',
		'observation',
		'created_by_user_id',
		'created_by_username',
		'state',
		'prority',
		'tags',
		'solar_system_id',
		'solar_system_name',
		'corporation_id',
		'corporation_name',
		'corporation_ticker',
		'alliance_id',
		'alliance_name',
		'alliance_ticker',
		'score',
		'reviewed_by_user_id',
		'reviewed_by_username',
		'created_at',
		'updated_at'
	];
}

