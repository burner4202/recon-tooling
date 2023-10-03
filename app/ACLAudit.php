<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class ACLAudit extends Model
{
	use Sortable;
	protected $table = 'acl';

	protected $fillable = ['acl_hash', 'acl_added_by', 'acl_name', 'acl_total_characters', 'acl_created_time', 'acl_raw', 'acl_public'];
}



