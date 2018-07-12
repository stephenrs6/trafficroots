<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AffiliateConversion extends Model
{
	protected $fillable = array('affiliate_id', 'site_id', 'zone_id', 'ad_id', 'cpm', 'country_id', 'state_code', 'city_code', 'platform', 'os', 'browser', 'impressions', 'clicks', 'stat_date');
	protected $table = 'affiliate_conversions';
}
