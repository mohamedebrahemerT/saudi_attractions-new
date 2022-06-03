<?php

namespace App\Models;

//use Dimsav\Translatable\Translatable;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Event
 * @package App\Models
 * @version December 11, 2017, 9:24 am UTC
 *
 * @property string title
 * @property date start_date
 * @property date end_date
 * @property string address
 * @property string address_url
 * @property string description
 * @property string start_price
 * @property string end_price
 */
class Event extends Model
{
    use SoftDeletes;
   // use Translatable;

    public $table = 'events';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'title',
        'start_date',
        'end_date',
        'address',
        'address_url',
        'description',
        'start_price',
        'end_price',
        'event_day_id',
        'media_id',
        'is_featured',
        'lat',
        'lng',
        'number_of_likes',
        'cash_on_delivery',
        'credit_card',
        'pay_later',
        'max_of_pay_later_tickets',
        'max_of_cash_tickets',
        'national_id',
        'is_liked',
        'number_of_going',
        'draft',
        'is_editable',
        'week_suggest',
        'arabic_notification_title',
        'arabic_notification_description',
        'english_notification_title',
        'english_notification_description',
        'max_of_free_tickets',
        'free'
    ];

    public $translationModel = 'App\EventLocale';


    public $translatedAttributes = ['title', 'description', 'address'];


    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required',
        'start_date' => 'required|date',
        'end_date' => 'nullable|after_or_equal:start_date|date',
        'address' => 'required',
        'address_url' => 'required',
        // 'address_url' => 'required|url',
        'description' => 'required',
        'start_price' => 'required|numeric',
        'lat' => 'required|numeric',
        'lng' => 'required|numeric',
        'end_price' => 'nullable|numeric',
        'image'  => 'required|mimes:jpeg,jpg,png,svg',
        'max_of_pay_later_tickets' => 'nullable|numeric|min:0',
        'max_of_cash_tickets' => 'nullable|numeric|min:0',
        'max_of_free_tickets'=> 'nullable|numeric|min:0',
//        'event_tickets.*.type' => 'required',
//        'event_tickets.*.description' => 'required',
//        'event_tickets.*.price' => 'numeric',
//        'event_tickets.*.number_of_tickets' => 'numeric|min:0',
        'event_days.*.start_date' => 'required|date',
        'event_days.*.start_time' => 'required',
        'event_days.*.end_time' => 'nullable',
        'ticket_dates.*.date' => 'required|date',
        'ticket_dates.*.time' => 'required',
        'categories' => '',
        'images.*' => 'mimes:jpeg,jpg,png,svg',
        'tags.*.name' => '',
        'tags.*.image' => 'mimes:jpeg,jpg,png,svg',
        'social_media.*.url'=>'nullable|url',
        'social_media.*.name'=>'nullable',
        'arabic_notification_title' => 'nullable',
        'arabic_notification_description' => 'nullable',
        'english_notification_title' => 'nullable',
        'english_notification_description' => 'nullable',

    ];

    public function gallery()
    {
        return $this->belongsToMany('App\Media','events_media','event_id','media_id');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Models\Category','events_category','event_id','category_id');
    }

    public function sub_categories()
    {
        return $this->belongsToMany('App\Models\SubCategory','events_subCategory','event_id','sub_category_id');
    }

    public function event_days()
    {
        return $this->hasMany('App\EventDay');
    }

    public function media()
    {
        return $this->belongsTo('App\Media');
    }

    public function social_media()
    {
        return $this->belongsToMany('App\Models\SocialMedia','events_social_media','event_id','social_media_id')->withPivot('url')->withPivot('name');
    }

    public function user()
    {
        return $this->belongsToMany('App\User','liked_events','event_id','user_id');
    }

    public function event_tickets()
    {
        return $this->hasMany('App\EventTicket');
    }

    public function ticket_dates()
    {
        return $this->hasMany('App\TicketDate');
    }

    public function orders()
    {
        return $this->hasMany('App\Order');
    }

    public function tags()
    {
        return $this->hasMany('App\Tag');
    }
}
