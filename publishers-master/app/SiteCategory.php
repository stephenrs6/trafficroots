<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SiteCategory extends Model
{
	protected $fillable = array('site_id', 'category', 'created_at', 'updated_at');
	protected $table = 'site_category';
}

