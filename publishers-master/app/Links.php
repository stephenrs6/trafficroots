<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Links extends Model
{
    protected $fillable = ['user_id','category','link_name','url','status'];
    
    public function category_type()
    {
        return $this->belongsTo('App\Category', 'category');
    }
    
    public function status_type()
    {
        return $this->belongsTo('App\StatusType', 'status');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
