<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class PublicContracts extends Model
{
	use Sortable;

	/**
     * The database table used by the model.
     *
     * @var string
     */

	protected $table = 'public_contracts';

	protected $fillable = [

		'contract_id',
		'type_id',
		'type_name',
		'region_id',
		'region_name',
		'price',
		'date_issued',
		'date_expired',
		'issuer_id',
		'character_name',
		'corporation_id',
		'corporation_name',
		'alliance_id',
		'alliance_name',
		'showinfo_link',
		'is_carrier',
		'is_fax',
		'is_dread',
		'is_super',
		'is_titan',
		'is_npc_delve',
		'contract_info',
		'standing'

	];

	protected $sortable = [

		'contract_id',
		'type_id',
		'type_name',
		'region_id',
		'region_name',
		'price',
		'date_issued',
		'date_expired',
		'issuer_id',
		'character_name',
		'corporation_id',
		'corporation_name',
		'alliance_id',
		'alliance_name',
		'showinfo_link',
		'is_carrier',
		'is_fax',
		'is_dread',
		'is_super',
		'is_titan',
		'is_npc_delve',
		'contract_info',
		'standing',
		'created_at',
		'updated_at'

	];
}
