<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketDate extends Model
{
    public $fillable = [
        'date',
        'time',
        'event_id'
    ];

    public static $rules = [
        'date' => 'required',
        'time' => 'required',
        'event_id' => 'required'
    ];

    public function event()
    {
        return $this->belongsTo('App\Models\Event');
    }

    public function orders()
    {
        return $this->hasMany('App\Order');
    }
}
