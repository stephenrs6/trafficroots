<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BrowserStat extends Model
{
    protected $table = 'browser_stats';
    protected $fillable = ['user_id','site_id','zone_id','browser','stat_date','impressions','clicks','created_at','updated_at'];

}
