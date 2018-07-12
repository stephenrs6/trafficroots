<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayoutSettings extends Model
{
    protected $fillable = ['user_id','payment_method','minimum_payout','tax_status','tax_id','created_at','updated_at'];
}
