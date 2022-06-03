<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VenueOpeningHour extends Model
{
    public $fillable = [
        'start_time',
        'end_time',
        'is_closed',
        'venue_id',
        'venue_day_id'
    ];

    public function venue_days()
    {
        return $this->belongsTo('App\VenueDay','venue_day_id');
    }

    public function venues()
    {
        return $this->belongsTo('App\Models\Venue');
    }

}
