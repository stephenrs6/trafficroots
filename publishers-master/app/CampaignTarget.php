<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignTarget extends Model
{
    protected $fillable = ['user_id','campaign_id','themes','geos','states','counties','platforms','browsers','operating_systems','keywords','sites'];
    
    public function countries()
    {
    	return $this->belongsTo('App\Country', 'geos');
    }

    public function states()
    {
    	return $this->belongsTo('App\State', 'states');
    }
}
