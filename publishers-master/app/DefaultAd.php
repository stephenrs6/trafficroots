<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DefaultAd extends Model
{
    protected $table = 'default_ads';
    protected $fillable = ['location_type','affiliate_id','status','html_id','country_id','state_id','city_id','category_id','device_id','os_id','browser_id','link_id','media_id'];
    //
}
