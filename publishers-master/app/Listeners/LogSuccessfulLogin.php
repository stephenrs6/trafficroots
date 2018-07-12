<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use App\SystemLog;
use App\Http\Controllers\CUtil;
use Log;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
	    $user = $event->user;
	    $log_entry = $user->name.' logged in from '.$this->request->ip();
	    Log::info($log_entry);
	    //$cutil = new CUtil();
	    //$cutil->sendText($log_entry, '+19514915526');
	    $data = array('log' => $log_entry, 'created_at' => date('Y-m-d H:i:s'));
            $log = new SystemLog();
	    $log->fill($data);
	    $log->save();
    }
}
