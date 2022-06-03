<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventDay extends Model
{
    public $fillable = [
        'start_date',
        'start_time',
        'end_time',
        'event_id'
    ];

    public static $rules = [
        'start_date' => 'required',
        'start_time' => 'required',
        'end_time' => 'required',
        'event_id' => 'required'
        ];

    public function event()
    {
        return $this->BelongTo('App\Models\Event');
    }
}
