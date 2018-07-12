<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlatformStat extends Model
{
    protected $table = 'platform_stats';
    protected $fillable = ['user_id','site_id','zone_id','platform','stat_date','impressions','clicks','created_at','updated_at'];
}
