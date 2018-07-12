<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    protected $fillable = [
    	'site_id',
    	'zone_id',
    	'ad_id',
    	'ad_creative_id',
    	'country_id',
    	'state_code',
    	'city_code',
    	'platform',
    	'os',
    	'browser',
    	'source',
    	'impressions',
    	'clicks',
    	'date'
    ];
    
    public function site()
    {
    	return $this->belongsTo('App\Site');
    }
    
    public function zone()
    {
    	return $this->belongsTo('App\Zone');
    }
    
    public function ad()
    {
    	return $this->belongsTo('App\Ad');
    }

    public function country()
    {
        return $this->belongsTo('App\Country');
    }

    public function state()
    {
        return $this->belongsTo('App\State', 'state_code');
    }

    public function platformType()
    {
        return $this->belongsTo('App\Platform', 'platform');
    }

    public function operatingSystem()
    {
        return $this->belongsTo('App\OperatingSystem', 'os');
    }

    public function browserType()
    {
        return $this->belongsTo('App\Browser', 'browser');
    }

    public function city()
    {
        return $this->belongsTo('App\City', 'city_code');
    }
}