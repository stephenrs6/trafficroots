<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['user_id','bank_id','transaction_date','invoice','Status','StatusCode','StatusMessage','TransactionId','CaptureState','TransactionState','Amount','CardType','ApprovalCode','MaskedPAN','PaymentAccountDataToken'];
}

