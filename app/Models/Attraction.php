<?php

namespace App\Models;

//use Dimsav\Translatable\Translatable;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Attraction extends Model
{
    use SoftDeletes;
   // use Translatable;

    public $translationModel = 'App\AttractionLocale';


    public $translatedAttributes = ['title', 'description', 'address'];


    public $table = 'attractions';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'title',
        'address',
        'address_url',
        'description',
        'media_id',
        'lat',
        'lng',
        'contact_numbers',
        'is_featured',
        'is_sponsored',
        'number_of_likes',
        'draft',
        'cash_on_delivery',
        'credit_card',
        'pay_later',
        'max_of_pay_later_tickets',
        'max_of_cash_tickets',
        'is_editable',
        'week_suggest',
        'number_of_days',
        'national_id',
        'number_of_going',
        'arabic_notification_title',
        'arabic_notification_description',
        'english_notification_title',
        'english_notification_description',
        'media_id',
        'max_of_free_tickets',
        'free'
    ];


    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required',
        'address' => 'required',
        'address_url' => 'required',
        // 'address_url' => 'required|url',
        'description' => 'required',
        'lat' => '|numeric',
        'lng' => '|numeric',
        'number_of_days' => '|numeric|min:0',
        'max_of_pay_later_tickets' => 'nullable|numeric|min:0',
        'max_of_cash_tickets' => 'nullable|numeric|min:0',
        'max_of_free_tickets' => 'nullable|numeric|min:0',
        'image'  => 'nullable|mimes:jpeg,jpg,png,svg',
        'contact_numbers' => 'nullable',
        // 'contact_numbers.*' => 'regex:/^[0-9. -]+$/',
        'attraction_tickets.*.type' => 'required',
        'attraction_tickets.*.description' => 'required',
        'attraction_tickets.*.price' => 'required|numeric|min:0',
        'attraction_tickets.*.number_of_tickets' => 'required|numeric|min:0',
        'attraction_addons.*.name' => 'required',
        'attraction_addons.*.description' => 'required',
        'attraction_addons.*.price' => 'required|numeric|min:0',
        'attraction_addons.*.number_of_tickets' => 'required|numeric|min:0',
        'attraction_week_days.*.start_time' => 'nullable|required_with:attraction_week_days.*.end_time',
        'attraction_exceptional_dates.*.start_time' =>'nullable|required_with:attraction_exceptional_dates.*.end_time',
        'gallery'  => 'nullable',
        'gallery.*' =>'mimes:jpeg,jpg,png,svg,mp4,mov,ogg,qt',
        'categories'  => '',
        'sub_categories'  => '',
        'tags.*.name'  => 'required',
        'tags.*.image'  => 'required|mimes:jpeg,jpg,png,svg',
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
        return $this->belongsToMany('App\Media','attractions_media','attraction_id','media_id');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Models\Category','attractions_category','attraction_id','category_id');
    }

    public function sub_categories()
    {
        return $this->belongsToMany('App\Models\SubCategory','attractions_subCategory','attraction_id','sub_category_id');
    }

    public function social_media()
    {
        return $this->belongsToMany('App\Models\SocialMedia','attractions_social_media','attraction_id','social_media_id')->withPivot('url')->withPivot('name');
    }

    public function tags()
    {
        return $this->hasMany('App\AttractionTag');
    }

    public function user()
    {
        return $this->belongsToMany('App\User','liked_attractions','attraction_id','user_id');
    }

    public function attraction_tickets()
    {
        return $this->hasMany('App\AttractionTicket');
    }

    public function attraction_addons()
    {
        return $this->hasMany('App\AttractionAddon');
    }

    public function attraction_week_days()
    {
        return $this->hasMany('App\AttractionWeekDay');
    }

    public function attraction_exceptional_dates()
    {
        return $this->hasMany('App\AttractionExceptionalDate');
    }

    public function attraction_orders()
    {
        return $this->hasMany('App\OrderAttraction');
    }

}
