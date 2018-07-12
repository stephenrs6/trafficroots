<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PublisherBooking extends Model
{
    protected $table = 'publisher_bookings';
    protected $fillable = ['site_id','zone_id','pub_id','start_date','end_date','impressions_delivered','cost','revenue','comments'];
    //
}
