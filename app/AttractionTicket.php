<?php

namespace App;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttractionTicket extends Model
{

    use Translatable;
    use SoftDeletes;

    public $translationModel = 'App\AttractionTicketLocale';

    public $table = 'attraction_tickets';

    public $translatedAttributes = ['type', 'description'];

    public $fillable = [
        'type',
        'description',
        'price',
        'number_of_tickets',
        'attraction_id'
    ];

    public static $rules = [
        'type' => 'required',
        'description' => 'required',
        'price' => 'required|numeric',
        'number_of_tickets' => 'required|integer|min:0',
        'attraction_id' => 'required'
    ];

    public function attraction()
    {
        return $this->belongsTo('App\Models\Attraction');
    }

    public function attraction_orders()
    {
        return $this->hasMany('App\OrderAttraction');
    }

    public function attraction_week_days()
    {
        return $this->belongsToMany('App\AttractionWeekDay','ticket_options','attraction_ticket_id','attraction_week_day_id');
    }

    public function attraction_exceptional_dates()
    {
        return $this->belongsToMany('App\AttractionExceptionalDate','ticket_exceptional','attraction_ticket_id','attraction_exceptional_date_id');
    }

    public function ticket_order()
    {
        return $this->belongsToMany('App\OrderAttraction','user_attraction_tickets','attraction_ticket_id', 'order_id')->withPivot('number_of_tickets');
    }

}
