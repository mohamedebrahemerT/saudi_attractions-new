<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CityLocale extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];
}
