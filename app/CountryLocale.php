<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CountryLocale extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];
}
