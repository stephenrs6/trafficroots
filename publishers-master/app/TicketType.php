<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    protected $connection = 'tradm';
    protected $fillable = ['description','comments'];    //
}
