<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Log;
use App\User;

class AdminController extends Controller
{
    /**
	    *      * Create a new controller instance.
	    *           *
	    *                * @return void
	    *                     */
        public function __construct()
        {
            $this->middleware('auth');
            setlocale(LC_MONETARY, 'en_US.utf8');
	}	    

	public function getAdminPage()
	{
            $user = Auth::getUser();
	    if($user->allow_folders){
                $all_users = User::all();
                return view('tradm', array('users' => $all_users));
            }else{
	        return redirect('/home');
	    }

	}

	public function postLoginFromAdmin(Request $request)
	{
            $user = Auth::getUser();
	    if($user->allow_folders){
		if($request->login_user){
			Auth::loginUsingId($request->login_user);
		}
            }
	
   	    return redirect('/home');
	   
	}
}
