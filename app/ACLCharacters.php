<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class ACLCharacters extends Model
{
    use Sortable;
	protected $table = 'acl_characters';

	protected $fillable = ['aclc_hash', 'aclc_acl_hash', 'aclc_character_name', 'aclc_character_id', 'aclc_corporation_name', 'aclc_corporation_id', 'aclc_corporation_id', 'aclc_alliance_name', 'aclc_alliance_id', 'aclc_gice_name', 'aclc_state', 'aclc_role', 'aclc_action_date', 'aclc_member_type'];
}
