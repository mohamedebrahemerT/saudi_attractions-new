<?php

namespace App\Models;

//use Dimsav\Translatable\Translatable;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SocialMedia
 * @package App\Models
 * @version December 12, 2017, 9:20 am UTC
 *
 * @property string name
 */
class SocialMedia extends Model
{
    use SoftDeletes;
   // use Translatable;

    public $table = 'social_media';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'media_id'
    ];

    public $translationModel = 'App\SocialMediaLocale';


    public $translatedAttributes = ['name'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'image' => 'required|mimes:jpeg,jpg,png,svg'
    ];

    public function media()
    {
        return $this->belongsTo('App\Media');
    }

    public function events()
    {
        return $this->belongsToMany('App\Models\Event','events_social_media','social_media_id','event_id')->withPivot('url')->withPivot('name');
    }

    public function venues()
    {
        return $this->belongsToMany('App\Models\Venue','venues_social_media','social_media_id','venue_id')->withPivot('url')->withPivot('name');
    }

    public function attractions()
    {
        return $this->belongsToMany('App\Models\Attraction','attractions_social_media','social_media_id','attraction_id')->withPivot('url')->withPivot('name');
    }
}
