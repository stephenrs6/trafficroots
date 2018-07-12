<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['user_id','transaction_date','status','amount','method','created_at','updated_at'];
}

