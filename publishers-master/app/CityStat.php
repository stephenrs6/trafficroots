<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CityStat extends Model
{
    protected $table = 'city_stats';
    protected $fillable = ['user_id','site_id','zone_id','city_code','state_code','stat_date','impressions','clicks','created_at','updated_at'];

}
