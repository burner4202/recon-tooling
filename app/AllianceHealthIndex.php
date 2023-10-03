<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class AllianceHealthIndex extends Model
{
	use Sortable;

	protected $table = 'alliance_health_index';

	protected $fillable = ['key', 'alliance_id', 'alliance_name', 'alliance_ticker', 'ihub_count', 'health', 'average_adm', 'date'];

	public $sortable = ['key', 'alliance_id', 'alliance_name', 'alliance_ticker', 'ihub_count', 'health', 'average_adm', 'date', 'created_at', 'updated_at'];
}
