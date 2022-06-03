<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use SoftDeletes;

    public $table = 'notifications';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'english_title',
        'english_description',
        'arabic_title',
        'arabic_description',
        'type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'english_title'=> 'string',
        'english_description'=> 'string',
        'arabic_title'=> 'string',
        'arabic_description'=> 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'english_title'=> 'required',
        'english_description'=> 'required',
        'arabic_title'=> 'required',
        'arabic_description'=> 'required',
        'type' =>'required'
    ];

    
}
