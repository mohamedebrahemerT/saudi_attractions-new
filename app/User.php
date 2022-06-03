<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable  implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'mobile_number', 'forget_code', 'profile_image', 'is_blocked', 'birth_date', 'gender', 'ja_id',
        'facebook_id', 'google_id', 'country_id', 'city_id', 'user_role_id', 'flag', 'verified','address'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static $rules = [
        'name' => 'required',
        'email' => 'email|required|unique:users',
        'mobile_number' => 'nullable|numeric|min:12',
        'password' => 'required|min:6',
        'user_role_id' => 'required',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function media()
    {
        return $this->belongsTo('App\Media', 'profile_image');
    }

    public function liked_events()
    {
        return $this->belongsToMany('App\Models\Event','liked_events','user_id','event_id');
    }

    public function orders()
    {
        return $this->hasMany('App\Order');
    }

    public function user_ticket()
    {
        return $this->belongsToMany('App\EventTicket','user_tickets','user_id','event_ticket_id');
    }

    public function user_order()
    {
        return $this->belongsToMany('App\Order','user_tickets','user_id', 'order_id');
    }

    public function liked_venues()
    {
        return $this->belongsToMany('App\Models\Venue','liked_venues','user_id','venue_id');
    }

    public function liked_attractions()
    {
        return $this->belongsToMany('App\Models\Attraction','liked_attractions','user_id','attraction_id');
    }

    public function country()
    {
        return $this->belongsTo('App\Country');
    }

    public function city()
    {
        return $this->belongsTo('App\City');
    }

    public function role()
    {
        return $this->belongsTo('App\UserRole','user_role_id');
    }

    public function contact_us()
    {
        return $this->hasMany('App\ContactUs');
    }

    public function attraction_orders()
    {
        return $this->hasMany('App\OrderAttraction');
    }

    public function user_ticket_order()
    {
        return $this->belongsToMany('App\OrderAttraction','user_attraction_tickets','user_id', 'order_id');
    }
}
