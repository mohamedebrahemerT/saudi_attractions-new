<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
//use Dimsav\Translatable\Translatable;

/**
 * Class Category
 * @package App\Models
 * @version December 10, 2017, 10:54 am UTC
 *
 * @property string name
 */
class Category extends Model
{
    use SoftDeletes;
   // use Translatable;

    public $table = 'categories';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'media_id'
    ];

     public $translationModel = 'App\CategoryLocale';


     public $translatedAttributes = ['name'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'image' => 'required|mimes:jpeg,jpg,png,svg'
    ];

    public function media()
    {
        return $this->belongsTo('App\Media');
    }

    public function sub_categories()
    {
        return $this->hasMany('App\Models\SubCategory');
    }

    public function events()
    {
        return $this->belongsToMany('App\Models\Event','events_category','category_id','event_id');
    }

    public function venues()
    {
        return $this->belongsToMany('App\Models\Venue','venues_category','category_id','venue_id');
    }

    public function attractions()
    {
        return $this->belongsToMany('App\Models\Attraction','attractions_category','category_id','attraction_id');
    }
}
