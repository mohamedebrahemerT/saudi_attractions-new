<?php

namespace App;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use Translatable;

    public $translationModel = 'App\TagLocale';


    public $translatedAttributes = ['name'];

    public $fillable = [
        'name',
        'media_id',
        'event_id'
    ];

    public static $rules = [
        'name' => 'required',
        'media_id' => 'required|',
        'event_id' => 'required'
    ];

    public function event()
    {
        return $this->BelongTo('App\Models\Event');
    }

    public function media()
    {
        return $this->belongsTo('App\Media');
    }
}
