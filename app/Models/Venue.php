<?php

namespace App\Models;

//use Dimsav\Translatable\Translatable;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Venue extends Model
{
    use SoftDeletes;
   // use Translatable;

    public $table = 'venues';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'title',
        'address',
        'location',
        'description',
        'is_sponsored',
        'is_featured',
        'media_id',
        'lat',
        'lng',
        'is_brand',
        'email',
        'website',
        'telephone_numbers',
        'draft',
        'is_editable',
        'week_suggest',
        'arabic_notification_title',
        'arabic_notification_description',
        'english_notification_title',
        'english_notification_description',
    ];

    public $translationModel = 'App\VenueLocale';


    public $translatedAttributes = ['title', 'description', 'address'];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required',
        'address' => 'required',
        'email' => 'nullable|email',
        'website' => 'nullable',
        // 'website' => 'nullable|url',
        'location' => 'required',
        // 'location' => 'required|url',
        'description' => 'required',
        'lat' => 'required|numeric',
        'lng' => 'required|numeric',
        'telephone_numbers' => 'required',
        'telephone_numbers.*' => 'regex:/^[0-9. -]+$/',
        'image'  => 'required|mimes:jpeg,jpg,png,svg',
        'gallery' => 'required',
        'gallery.*' => 'mimes:jpeg,jpg,png,svg,mp4,mov,ogg,qt',
//        'categories' => 'required',
//        'sub_categories' => 'required',
        'venue_opening_hours.*.start_time' => 'nullable|required_with:venue_opening_hours.*.end_time',
        'social_media.*.url'=>'nullable|url',
        'social_media.*.name'=>'nullable',
        'arabic_notification_title' => 'nullable',
        'arabic_notification_description' => 'nullable',
        'english_notification_title' => 'nullable',
        'english_notification_description' => 'nullable',
];

    public function media()
    {
        return $this->belongsTo('App\Media');
    }

    public function gallery()
    {
        return $this->belongsToMany('App\Media','venues_media','venue_id','media_id');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Models\Category','venues_category','venue_id','category_id');
    }

    public function sub_categories()
    {
        return $this->belongsToMany('App\Models\SubCategory','venues_subCategory','venue_id','sub_category_id');
    }

    public function social_media()
    {
        return $this->belongsToMany('App\Models\SocialMedia','venues_social_media','venue_id','social_media_id')->withPivot('url')->withPivot('name');
    }

    public function venue_opening_hours()
    {
        return $this->hasMany('App\VenueOpeningHour');
    }

    public function user()
    {
        return $this->belongsToMany('App\User','liked_venues','venue_id','user_id');
    }

}
