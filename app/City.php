<?php

namespace App;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use Translatable;

    public $translationModel = 'App\CityLocale';


    public $translatedAttributes = ['name'];

    public $fillable = [
        'name',
        'country_id'
    ];

    public static $rules = [
        'name' => 'required',
        'country_id' => 'required',
    ];

    public function country()
    {
        return $this->belongsTo('App\Country');
    }

    public function users()
    {
        return $this->hasMany('App\User');
    }
}
