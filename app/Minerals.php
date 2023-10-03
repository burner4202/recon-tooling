<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Minerals extends Model
{
            /**
     * The database table used by the model.
     *
     * @var string
     */
        protected $table = 'minerals';

        protected $fillable = ['name', 'type_id', 'description', 'group_id', 'icon_id', 'portion_size'];
}
