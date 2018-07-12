<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Social\FacebookServiceProvider;
use App\Social\GoogleServiceProvider;
use GuzzleHttp\Exception\ClientException;
use Laravel\Socialite\Two\InvalidStateException;
use League\OAuth1\Client\Credentials\CredentialsException;
use Log;

class SocialController extends Controller
{
    protected $providers = [
        'facebook' => FacebookServiceProvider::class,
        'google' => GoogleServiceProvider::class,
    ];

    /**
     *  Create a new controller instance
     * 
     * @return  void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     *  Redirect the user to provider authentication page
     * 
     *  @param  string $driver
     *  @return \Illuminate\Http\Response
     */
    public function redirectToProvider($driver)
    {
        return (new $this->providers[$driver])->redirect();        
    }

    /**
     *  Handle provider response
     * 
     *  @param  string $driver
     *  @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($driver)
    {   
        try {
            return (new $this->providers[$driver])->handle();
        } catch (InvalidStateException $e) {
            Log::error($e->getMessage());
            return $this->redirectToProvider($driver);
        } catch (ClientException $e) {
            Log::error($e->getMessage());
            return $this->redirectToProvider($driver);
        } catch (CredentialsException $e) {
            Log::error($e->getMessage());
            return $this->redirectToProvider($driver);
        }
    }
}
