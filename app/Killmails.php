<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Killmails extends Model
{
	protected $table = 'killmails';
	
	protected $fillable = ['killmail_id', 'data', 'added_by'];
}

