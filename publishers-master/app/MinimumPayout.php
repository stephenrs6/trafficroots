<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MinimumPayout extends Model
{
	protected $fillable = ['amount'];
	protected $table = 'minimum_payout';
}
