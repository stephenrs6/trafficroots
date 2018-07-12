<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdCreative extends Model
{
	protected $table = 'ad_creatives';
	protected $fillable = ['ad_id','weight','status','media_id','link_id','folder_id','created_at','updated_at'];
}
