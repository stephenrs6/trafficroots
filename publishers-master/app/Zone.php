<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $fillable = [
    	'handle',
    	'site_id',
    	'pub_id',
    	'module_type',
    	'width',
    	'height',
    	'status',
    	'location_type',
    	'description'
    ];

    public function site()
    {
    	return $this->belongsTo('App\Site');
    }
    public function pub()
    {
    	return $this->belongsTo('App\User');
    }
    public function stats()
    {
        return $this->hasMany('App\Stat');
    }
    public function site_name()
    {
        return $this->hasOne('App\Site', 'id', 'site_id')->pluck('site_name');
    }
}
