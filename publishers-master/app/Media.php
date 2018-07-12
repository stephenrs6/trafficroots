<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = ['user_id','location_type','category','media_name','file_location','alternate_file','status'];

    public function category_type()
    {
        return $this->belongsTo('App\Category', 'category');
    }
    
    public function locationType()
    {
        return $this->belongsTo('App\LocationType', 'location_type');
    }

    public function status_type()
    {
        return $this->belongsTo('App\StatusType', 'status');
    }
}
