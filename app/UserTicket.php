<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTicket extends Model
{
    public $fillable = [
        'user_id',
        'event_ticket_id',
        'order_id',
        'ticket_number'
    ];

    public function event_ticket()
    {
        return $this->belongsTo('App\EventTicket');
    }

    public function orders()
    {
        return $this->hasMany('App\Order');
    }

    public function users()
    {
        return $this->hasMany('App\User');
    }
}
