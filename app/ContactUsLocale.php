<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactUsLocale extends Model
{
    public $timestamps = false;
    protected $fillable = ['address'];
}
