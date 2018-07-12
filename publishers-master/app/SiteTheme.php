<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SiteTheme extends Model
{
	protected $table = 'site_themes';
	protected $fillable = ['theme','created_at','updated_at'];
}
