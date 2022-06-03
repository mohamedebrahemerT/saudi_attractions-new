<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VenueDay extends Model
{
    public $fillable = [
        'day',
    ];

    public function venue_opening_hours()
    {
        return $this->hasMany('App\VenueOpeningHour');
    }

    public function attraction_opening_hours()
    {
        return $this->hasMany('App\AttractionOpeningHour');
    }

    public function attraction_ticket_days()
    {
        return $this->hasMany('App\AttractionTicketDay');
    }

    public function attraction_week_days()
    {
        return $this->hasMany('App\AttractionWeekDay');
    }

}
