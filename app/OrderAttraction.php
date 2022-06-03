<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderAttraction extends Model
{
    public $fillable = [
        'name',
        'email',
        'mobile_number',
        'payment_method',
        'total',
        'status',
        'attraction_id',
        'attraction_ticket_id',
        'attraction_addon_id',
        'attraction_week_day_id',
        'attraction_exceptional_date_id',
        'user_id',
        'order_number',
        'user_national_id',
        'is_canceled',
        'is_verified',
        'date'
    ];

    public static $rules = [
        'name' => 'required',
        'email' => 'email|required|unique:users',
        'mobile_number' => 'required|numeric|min:12',
        'payment_method' => 'required',
        'order_number' => 'integer'
    ];

    public function attraction()
    {
        return $this->belongsTo('App\Models\Attraction');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function attraction_ticket()
    {
        return $this->belongsTo('App\AttractionTicket', 'attraction_id');
    }

    public function attraction_addon()
    {
        return $this->belongsTo('App\AttractionAddon');
    }

    public function user_ticket_order()
    {
        return $this->belongsToMany('App\User','user_attraction_tickets','order_id', 'user_id');
    }

    public function ticket_order()
    {
        return $this->belongsToMany('App\AttractionTicket','user_attraction_tickets','order_id', 'attraction_ticket_id')->withPivot('number_of_tickets');
    }

    public function addon_order()
    {
        return $this->belongsToMany('App\AttractionAddon','user_attraction_tickets','order_id', 'attraction_addon_id')->withPivot('number_of_tickets');
    }

    public function order_week_day()
    {
        return $this->belongsToMany('App\AttractionWeekDay','user_attraction_tickets','order_id', 'attraction_week_day_id');
    }

    public function order_exceptional_date()
    {
        return $this->belongsToMany('App\AttractionExceptionalDate','user_attraction_tickets','order_id', 'attraction_exceptional_date_id');
    }

}
