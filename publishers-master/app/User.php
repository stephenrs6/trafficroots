<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','addr','city','state','zip','tax_id','country_code','email','password','phone','user_type','allow_folders','settings','token_expires','created_at','updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'settings' => 'array'
    ];


    public function zones()
    {
        return $this->hasMany('App\Zone','pub_id');
    }

    public function sites()
    {
        return $this->hasMany('App\Site');
    }

    public function campaigns()
    {
        return $this->hasMany('App\Campaign');
    }
    
    public function getMedia()
    {
        return $this->hasMany('App\Media');
    }
    
    public function getLinks()
    {
        return $this->hasMany('App\Links');
    }
    
    public function getFolders()
    {
        return $this->hasMany('App\Folder');
    }
}
