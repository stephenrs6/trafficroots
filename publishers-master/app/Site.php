<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

use App\Ad;

class Site extends Model
{
	//use SoftDeletes;
	//protected $dates = ['deleted_at'];
	
    protected $fillable = [
    	'site_name',
    	'site_url',
    	'site_theme',
    	'user_id',
    	'site_handle',
        'status_type'
    ];

    public function zones()
    {
    	return $this->hasMany('App\Zone');
    }

    public function user()
    {
    	return $this->belongsTo('App\User');
    }
    
    public function addZone($description, $location_type)
    {
        $pub_id = $this->user_id;
        $handle = bin2hex(random_bytes(5));
        $this->zones()->create(compact('description','location_type','pub_id','handle'));
        $this->insertFirstAd($handle, $location_type);
    }

    public function getStats()
    {
        return $this->hasMany('App\Stat');
    }
    private function insertFirstAd($handle, $location_type)
    {
        try {
            $ad = new Ad();
            $data = array();
            $data['zone_handle'] = $handle;
            $data['location_type'] = $location_type;
            $data['weight'] = 100;
            $data['status'] = 1;
            $ad->fill($data);
            $ad->save();
            return true;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }
}
