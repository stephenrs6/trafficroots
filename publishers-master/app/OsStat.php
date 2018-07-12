<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OsStat extends Model
{
    protected $table = 'os_stats';
    protected $fillable = ['user_id','site_id','zone_id','os','stat_date','impressions','clicks','created_at','updated_at'];

}
