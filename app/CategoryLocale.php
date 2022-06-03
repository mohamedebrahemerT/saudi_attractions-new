<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryLocale extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];
}
