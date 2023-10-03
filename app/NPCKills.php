<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class NPCKills extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'npc_kills';

    protected $fillable = ['npc_kill_id', 'solar_system_id', 'constellation_id', 'region_id', 'solar_system_name', 'constellation_name', 'region_name', 'npc_kills'];
}
