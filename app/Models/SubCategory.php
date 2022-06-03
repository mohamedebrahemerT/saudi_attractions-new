<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SubCategory
 * @package App\Models
 * @version December 10, 2017, 12:21 pm UTC
 *
 * @property string name
 */
class SubCategory extends Model
{
    use SoftDeletes;
   use Translatable;

    public $table = 'sub_categories';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'category_id'
    ];

    public $translationModel = 'App\SubCategoryLocale';


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
        'name' => 'required'
    ];

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function events()
    {
        return $this->belongsToMany('App\Models\Event','events_subCategory','sub_category_id','event_id');
    }

    public function venues()
    {
        return $this->belongsToMany('App\Models\Venue','venues_subCategory','sub_category_id','venue_id');
    }

    public function attractions()
    {
        return $this->belongsToMany('App\Models\Attraction','attractions_subCategory','sub_category_id','attraction_id');
    }
}
