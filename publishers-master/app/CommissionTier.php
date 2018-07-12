<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommissionTier extends Model
{
	protected $fillable = array('publisher_factor', 'description', 'created_at', 'updated_at');
	protected $table = 'commission_tiers';
}