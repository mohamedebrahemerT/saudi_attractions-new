<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class AttractionExceptionalDate extends Model
{

    public $fillable = [
        'start_time',
        'end_time',
        'date',
        'attraction_id'
    ];


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
        return $this->belongsToMany('App\AttractionTicket','ticket_exceptional','attraction_exceptional_date_id','attraction_ticket_id');
    }

    public function addons()
    {
        return $this->belongsToMany('App\AttractionAddon','addon_exceptional','attraction_exceptional_date_id','attraction_addon_id');
    }

    public function order_exceptional_date()
    {
        return $this->belongsToMany('App\OrderAttraction','user_attraction_tickets','attraction_exceptional_date_id', 'order_id')->withPivot('start_time');
    }
}
