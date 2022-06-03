<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttractionTicketNumber extends Model
{
    public $fillable = [
        'user_id',
        'attraction_ticket_id',
        'order_id',
        'ticket_number'
    ];

    public function attraction_ticket()
    {
        return $this->belongsTo('App\AttractionTicket');
    }

    public function orders()
    {
        return $this->hasMany('App\OrderAttraction');
    }

    public function users()
    {
        return $this->hasMany('App\User');
    }
}
