<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable =['image'];

    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }

    public function events()
    {
        return $this->belongsToMany('App\Models\Event','events_media','media_id','event_id');
    }

    public function venues()
    {
        return $this->belongsToMany('App\Models\Venue','venues_media','media_id','venue_id');
    }

    public function attractions()
    {
        return $this->belongsToMany('App\Media','attractions_media','media_id', 'attraction_id');
    }

}
