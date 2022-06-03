<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $fillable = [
        'name',
        'email',
        'mobile_number',
        'number_of_tickets',
        'payment_method',
        'total',
        'status',
        'event_id',
        'event_ticket_id',
        'ticket_date_id',
        'user_id',
        'order_number',
        'user_national_id',
        'is_canceled',
        'is_verified'
    ];

    public static $rules = [
        'name' => 'required',
        'email' => 'email|required|unique:users',
        'mobile_number' => 'required|numeric|min:12',
        'number_of_tickets' => 'required|numeric',
        'payment_method' => 'required',
        'order_number' => 'integer'
    ];

    public function event()
    {
        return $this->belongsTo('App\Models\Event');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function event_ticket()
    {
        return $this->belongsTo('App\EventTicket');
    }

    public function ticket_date()
    {
        return $this->belongsTo('App\TicketDate');
    }

    public function user_order()
    {
        return $this->belongsToMany('App\User','user_tickets','order_id', 'user_id');
    }
}
