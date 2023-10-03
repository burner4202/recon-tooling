<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class APICalls extends Model
{
   	use Sortable;
	protected $table = 'api_calls';

	protected $fillable = ['apc_ip_address', 'apc_user_agent', 'apc_endpoint', 'apc_response', 'apc_gsf_username'];
}
  