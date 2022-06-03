<?php

namespace App;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class AttractionTag extends Model
{
    use Translatable;

    public $translationModel = 'App\AttractionTagLocale';


    public $translatedAttributes = ['name'];

    public $fillable = [
        'name',
        'media_id',
        'attraction_id'
    ];

    public static $rules = [
        'name' => 'required',
        'media_id' => 'required',
        'attraction_id' => 'required'
    ];

    public function attraction()
    {
        return $this->BelongTo('App\Models\Attraction');
    }

    public function media()
    {
        return $this->belongsTo('App\Media');
    }
}
