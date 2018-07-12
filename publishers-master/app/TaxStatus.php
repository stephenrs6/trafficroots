<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaxStatus extends Model
{
	protected $fillable = ['description'];
	protected $table = 'tax_status';
}
