<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Spend extends Model
{
	protected $fillable['id', 'user_id', 'spend_date', 'spent', 'created_at', 'updated_at'];
	protected $table = 'spend';
}
