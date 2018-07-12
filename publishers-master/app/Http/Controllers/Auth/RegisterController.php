<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Log;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'g-recaptcha-response' => 'required',          
        ]);
    }

    /**
     * cURL Google for reCaptcha validation
     * @param Request
     * @return boolean
     * @author Cary White
     */
    protected function reCaptcha()
    {
	$request = request();
        $secret = '6LfwKzUUAAAAAEnr87OfoR3EUqED4ewpVLfZ2SAB';
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $input = $request->all();
        $query = 'secret='.$secret.'&response='.$input['g-recaptcha-response'].'&remoteip='.$request->ip();
        $ch = curl_init();  
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_HEADER, false); 
        curl_setopt($ch, CURLOPT_POST, count($query));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);    
 
        $output=json_decode(curl_exec($ch));
        Log::info(print_r($output, true));
        curl_close($ch);
        
        if(isset($output->success)){
            return $output->success;
        }else{
            return false;
        }

    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
	if($this->reCaptcha()){
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'company' => $data['company'],
            'user_type' => 0,
            'password' => bcrypt($data['password']),
        ]);
	return $user;
	}else{
	    Log::info('Invalid reCaptcha!');
	    die('Invalid reCaptcha!');
	}
    }
}
