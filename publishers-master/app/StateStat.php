<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StateStat extends Model
{
    protected $table = 'state_stats';
    protected $fillable = ['user_id','site_id','zone_id','state_code','stat_date','impressions','clicks','created_at','updated_at'];

}
