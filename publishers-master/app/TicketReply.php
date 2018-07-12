<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketReply extends Model
{
	//
	protected $fillable = ['ticket_id','comments','admin','created_at','updated_at'];
	protected $table = 'ticket_replies';
        protected $connection = 'tradm';

	public function ticket()
	{
            return $this->belongsTo('App\Ticket');
	}
}
