<?php

namespace App\Social;

use App\User;
use App\Http\Controllers\PublicController;

class GoogleServiceProvider extends AbstractServiceProvider
{
   /**
     *  Handle Facebook response
     * 
     *  @return Illuminate\Http\Response
     */
    public function handle()
    {
        $user = $this->provider->user();
        
        $existingUser = User::whereEmail($user->email)->orWhere('settings->google_id', $user->id)->first();

        if ($existingUser) {
            $settings = $existingUser->settings;

            if (! isset($settings['google_id'])) {
                $settings['google_id'] = $user->id;
                $existingUser->settings = $settings;
                $existingUser->save();
            }

            return $this->login($existingUser);
        }

        $newUser = $this->register([
            'name' => $user->name,
            'email' => $user->email,
            'settings' => [
            'google_id' => $user->id,                
            ]
        ]);        
        
        /* register user at sendlane */
        $first_name = '';
        $last_name = '';

        $name = explode(" ", $user->name);
        if(is_array($name) && count($name) == 2){
            $first_name = $name[0];
            $last_name = $name[1]; 
        }
        if(is_array($name) && count($name) == 1){
            $first_name = $name[0];
        }
        if(is_array($name) && count($name) > 2){
            $first_name = $name[0];
            $suffix = isset($name[3]) ? $name[3] : '';
            $last_name = trim($name[1].' '.$name[2].' '.$suffix);
        }
        $sendlane = new PublicController();
        $data = array();
        $data['email'] = $user->email;
        $data['first_name'] = $first_name;
        $data['last_name'] = $last_name;
        $data['list_id'] = 3;
        $result = $sendlane->sendlaneSubscribe($data);

        return $this->login($newUser);
    }       
}
