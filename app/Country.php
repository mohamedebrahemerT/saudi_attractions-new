<?php

namespace App;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use Translatable;

    public $translationModel = 'App\CountryLocale';


    public $translatedAttributes = ['name'];

    public $fillable = [
        'name',
    ];

    public static $rules = [
        'name' => 'required',
    ];

    public function cities()
    {
        return $this->hasMany('App\City');
    }

    public function users()
    {
        return $this->hasMany('App\User');
    }
}
