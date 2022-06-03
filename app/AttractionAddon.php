<?php

namespace App;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttractionAddon extends Model
{
    use Translatable;
    use SoftDeletes;

    public $translationModel = 'App\AttractionAddonLocale';

    public $table = 'attraction_addons';

    public $translatedAttributes = ['name', 'description'];

    public $fillable = [
        'name',
        'description',
        'price',
        'number_of_tickets',
        'attraction_id'
    ];

    public static $rules = [
        'name' => 'required',
        'description' => 'required',
        'price' => 'required|numeric',
        'number_of_tickets' => 'required|integer|min:0',
        'attraction_id' => 'required'
    ];

    public function attraction()
    {
        return $this->belongsTo('App\Models\Attraction');
    }

    public function attraction_orders()
    {
        return $this->hasMany('App\OrderAttraction');
    }

    public function attraction_week_days()
    {
        return $this->belongsToMany('App\AttractionWeekDay','addon_options','attraction_addon_id','attraction_week_day_id');
    }

    public function attraction_exceptional_dates()
    {
        return $this->belongsToMany('App\AttractionExceptionalDate','addon_exceptional','attraction_addon_id','attraction_exceptional_date_id');
    }

    public function addon_order()
    {
        return $this->belongsToMany('App\OrderAttraction','user_attraction_addons','attraction_addon_id', 'order_id');
    }
}
