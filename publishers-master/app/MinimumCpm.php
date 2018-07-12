<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MinimumCpm extends Model
{
	protected $fillable = array('state_name', 'cpc_desktop', 'cpm_desktop', 'cpc_mobile', 'cpm_mobile', 'created_at', 'updated_at');
	protected $table = 'minimum_cpm';
}
