<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $connection = 'tradm';
    protected $fillable = ['user_id','type','subject','status','description','comments'];
	public function replies()
	{
	  return $this->hasMany('App\TicketReply', 'ticket_id', 'id');
        }

        public function reply($comments)
	{
            $reply_info = array('ticket_id' => $this->id, 'comments' => $comments, 'created_at' => date('Y-m-d H:i:s'));
            $reply = new TicketReply();
	    $reply->fill($reply_info);
	    return $reply->save();
	}

}
