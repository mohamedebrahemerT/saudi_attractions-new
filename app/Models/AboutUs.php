<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class AboutUs extends Model
{
    use SoftDeletes;
    use Translatable;

    public $translationModel = 'App\AboutUsLocale';


    public $translatedAttributes = ['paragraph'];



    public $table = 'about_uses';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'paragraph'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'paragraph' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'paragraph' => 'required'
    ];

    
}
