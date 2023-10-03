<?php

/*
 * Goonswarm Federation Recon Tools
 *
 * Developed by scopehone <scopeh@gmail.com>
 * In conjuction with Natalya Spaghet & Mindstar Technology 
 *
 */

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notifications';

    protected $fillable = ['notification_id', 'sender_id', 'sender_type', 'text', 'timestamp', 'type'];
}
