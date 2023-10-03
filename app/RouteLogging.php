<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class RouteLogging extends Model
{
	use Sortable;

	protected $table = 'route_logging';

	protected $fillable = ['user_id', 'username', 'ip', 'url'];

	public $sortable = ['user_id', 'username', 'ip', 'url'];

	
}
