<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CountryStat extends Model
{
    protected $table = 'country_stats';
    protected $fillable = ['user_id','site_id','zone_id','country_code','stat_date','impressions','clicks','created_at','updated_at'];

}
