<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = ['user_id','campaign_type','campaign_category','location_type','campaign_name','status','bid','daily_budget','frequency_capping'];

    public function stats()
    {
    	return $this->hasManyThrough(
    		'App\Stat', 'App\Creative', 
    		'campaign_id', 'ad_creative_id'
        );
    }

    public function status_type()
    {
    	return $this->belongsTo('App\StatusType', 'status');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function category()
    {
    	return $this->belongsTo('App\Category', 'campaign_category');
    }

    public function type()
    {
    	return $this->belongsTo('App\CampaignType', 'campaign_type');
    }
    public function targets()
    {
        return $this->hasOne('App\CampaignTarget');
    }
}

