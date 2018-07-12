<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
	protected $table = 'system_log';
	protected $fillable = ['log', 'created_at', 'updated_at'];
}
