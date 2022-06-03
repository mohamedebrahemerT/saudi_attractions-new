<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class AttractionWeekDay extends Model
{


    public $fillable = [
        'start_time',
        'end_time',
        'is_closed',
        'attraction_id',
        'venue_day_id'
    ];

    public function venue_days()
    {
        return $this->belongsTo('App\VenueDay', 'venue_day_id');
    }

    public function attractions()
    {
        return $this->belongsTo('App\Models\Attraction');
    }

    public function attraction_orders()
    {
        return $this->hasMany('App\OrderAttraction');
    }

    public function types()
    {
        return $this->belongsToMany('App\AttractionTicket','ticket_options','attraction_week_day_id','attraction_ticket_id');
    }

    public function addons()
    {
        return $this->belongsToMany('App\AttractionAddon','addon_options','attraction_week_day_id','attraction_addon_id');
    }

    public function order_week_day()
    {
        return $this->belongsToMany('App\OrderAttraction','user_attraction_tickets','attraction_week_day_id', 'order_id')->withPivot('start_time');
    }
}
