<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAttractionTicket extends Model
{
    public $fillable = [
        'user_id',
        'attraction_ticket_id',
        'order_id',
        'ticket_number',
        'attraction_addon_id',
        'attraction_exceptional_date_id',
        'attraction_week_day_id',
        'number_of_tickets'
    ];

    public function attraction_ticket()
    {
        return $this->belongsTo('App\AttractionTicket');
    }

    public function ticket_options()
    {
        return $this->belongsTo('App\AttractionWeekDay');
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
