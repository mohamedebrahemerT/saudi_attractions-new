<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    protected $table = 'contact_uses';

    protected $fillable = ['subject', 'message', 'user_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
