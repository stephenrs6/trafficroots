<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\User;
use Log;

class ConfirmController extends Controller
{
    public function confirm(Request $request)
    {
        if(strlen($request->handle)){
	    $userid = intval(Redis::get($request->handle));
	    if($userid){
                $user = User::where('id', $userid)->where('status', 0)->get();
		if(sizeof($user)){
			$username = $user[0]->name;
			$useremail = $user[0]->email;
			User::where('id', $userid)->update(['status' => 1]);
			Log::info($username.' confirmed email address '.$useremail);
			$request->session()->flash('status', 'Thank you for confirming!');
			if(Redis::del($request->handle)) Log::info('Confirm Key ' . $request->handle . ' removed.');
		}else{
                	if(Redis::del($request->handle)) Log::info('Confirm Key ' . $request->handle . ' removed.');
                }
            }
        }
        return redirect('/home');
    }    //
}
