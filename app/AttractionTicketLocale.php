<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttractionTicketLocale extends Model
{

    protected $table = 'attraction_ticket_locales';
    protected $fillable = ['type', 'description'];
}
