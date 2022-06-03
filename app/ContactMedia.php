<?php

namespace App;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class ContactMedia extends Model
{
    use Translatable;

    public $translationModel = 'App\ContactMediaLocale';


    public $translatedAttributes = ['name'];

    public $fillable = [
        'name',
        'media_id',
        'url', 
        'contactus_id'
    ];

    public static $rules = [
        'name' => 'required',
        'media_id' => 'required',
        'url' => 'required',
        'contactus_id' => 'required'
    ];

    public function contactUs()
    {
        return $this->BelongTo('App\Models\ContactUs', 'contactus_id');
    }

    public function media()
    {
        return $this->belongsTo('App\Media');
    }
}
