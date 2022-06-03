<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttractionAddonLocale extends Model
{
    protected $table = 'attraction_addon_locales';
    protected $fillable = ['name', 'description'];
}
