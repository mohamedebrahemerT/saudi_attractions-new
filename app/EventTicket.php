<?php

namespace App;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class EventTicket extends Model
{
    use Translatable;

    public $translationModel = 'App\EventTicketLocale';


    public $translatedAttributes = ['type', 'description'];

    public $fillable = [
        'type',
        'description',
        'price',
        'number_of_tickets',
        'event_id'
    ];

    public static $rules = [
        'type' => 'required',
        'description' => 'required',
        'price' => 'required|numeric',
        'number_of_tickets' => 'required|integer|min:0',
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

    public function user_ticket()
    {
        return $this->belongsToMany('App\User','user_tickets','event_ticket_id','user_id');
    }

}
