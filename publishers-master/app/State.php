<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'states';
    protected $fillable = ['state_name','country_id'];
 
    /**
	    *      * Get the phone record associated with the user.
	    *           */
        public function country_name()	    
	{
            return $this->hasOne('App\Country', 'id', 'country_id');
	}   
}
