<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paypal extends Model
{
	protected $fillable = array('paypal_id', 'bank_id', 'created_at', 'updated_at');
	protected $table = 'minimum_cpm';
}
