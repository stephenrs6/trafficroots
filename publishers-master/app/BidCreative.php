<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BidCreative extends Model
{
    //TrafficRoots Bid Creative Model
    protected $table = 'creatives';
    protected $fillable = ['bid_id','weight','status','html_id','link_id'];
    //
}
