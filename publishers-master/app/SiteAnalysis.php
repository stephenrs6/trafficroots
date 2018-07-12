<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SiteAnalysis extends Model
{
	protected $fillable = array('site_handle', 'stat_date', 'geo', 'state', 'city', 'device', 'browser', 'os', 'impressions');
	protected $table = 'site_analysis';
}