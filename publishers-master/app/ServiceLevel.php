<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceLevel extends Model
{
	protected $table = 'service_level';
	protected $fillable = ['impressions','created_at'];
}
