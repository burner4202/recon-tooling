<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class MarketPrices extends Model
{
     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'market_prices';

    protected $fillable = ['market_id', 'type_id', 'date', 'highest', 'lowest', 'order_count', 'volume', 'average'];
}
