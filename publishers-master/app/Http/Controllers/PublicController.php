<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Log;
use Redis;
use Cache;

class PublicController extends Controller
{

    public function aboutUs()
    {
         return view('about');
    }    //

    public function getPrivacyPage()
    {
        return view('privacy');
    }
    public function getLandingPage()
    {
        /* u.s. data */
         $minutes = (60 * 24);
         $result = Cache::remember('us_data', $minutes, function(){
             $sql = "SELECT SUM(impressions) as impressions, state
                FROM site_analysis
                JOIN states ON site_analysis.state = states.state_name
                WHERE stat_date >=  DATE_SUB(CURDATE(), INTERVAL 10 DAY)
                AND geo = 'US'
                AND legal = 1
                GROUP BY state
                ORDER BY impressions DESC
                LIMIT 20;";
             return DB::select($sql);
         });
         $targeted_traffic = 0;
         foreach($result as $row){
             $targeted_traffic += $row->impressions;

         }
         $us_display = '<table id="us_table" class="table table-border table-hover table-stripe"><thead><tr><th>%</th><th>State</th></tr></thead><tbody>';
         foreach($result as $row){
             $factor = round(($row->impressions / $targeted_traffic) * 100, 2);
             $us_display .= '<tr><td>'.$factor.' %</td><td>'.$row->state.'</td></tr>';
         }
         $us_display .= '</tbody></table>';

        /* global data */
         $result = Cache::remember('global_data', $minutes, function (){
                $sql = "SELECT SUM(impressions) as impressions, countries.country_name
                FROM site_analysis
                JOIN countries ON site_analysis.geo = countries.country_short
                WHERE stat_date >= DATE_SUB(CURDATE(), INTERVAL 10 DAY)
                AND countries.targeted = 1
                GROUP BY country_name
                ORDER BY impressions DESC
                LIMIT 20;";
             return DB::select($sql);
         });
         $targeted_traffic = 0;
         foreach($result as $row){
             $targeted_traffic += $row->impressions;

         }
         $geo_display = '<table id="geo_table" class="table table-border table-hover table-stripe"><thead><tr><th>%</th><th>Country</th></tr></thead><tbody>';
         foreach($result as $row){
             $factor = round(($row->impressions / $targeted_traffic) * 100, 2);
             $geo_display .= '<tr><td>'.$factor.' %</td><td>'.$row->country_name.'</td></tr>';
         }
         $geo_display .= '</tbody></table>';

        return view('landing',['us_display' => $us_display, 'geo_display' => $geo_display]);
    }

    public function checkCache(){
        $cache = Redis::get('SENDLANE');
        if($cache){
            if($cache > 3) {
                sleep(4);
                return false;
            }
            
            Redis::incr('SENDLANE');
            return true;
        }
        Redis::incr('SENDLANE');
        Redis::expire('SENDLANE',2);
        return true;

    }    
    public function sendlaneSubscribe($data = array())
    {
        /* use Sendlane API to register new sign ups
         * allow list_id to be passed as argument, 
         * so this function can be used for
         * subscribing to future lists, too
         */
       
        if(count($data) && $this->checkCache()){
            Log::info('Subscribing '.$data['email'].' to list '.$data['list_id']);
            $return = array();
            $return['result'] = 'error';
            $api_key = env('SENDLANE_API_KEY');
            $hash_key = env('SENDLANE_HASH_KEY');
            $api_url = env('SENDLANE_API_URL');
            $command = 'list-subscriber-add';
            $url = $api_url.$command;
            $post = array('api' => $api_key, 'hash' => $hash_key, 'list_id' => $data['list_id'], 'email' => $data['email'], 'first_name' => $data['first_name'], 'last_name' => $data['last_name']);
            $ch = curl_init();
            Log::info('cUrl initiated');
            curl_setopt($ch,CURLOPT_URL, $url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            $result = curl_exec($ch);
            if (!curl_errno($ch)) {
                $info = curl_getinfo($ch);
                if($info['http_code'] == 200){
                    Log::info( 'Took '. $info['total_time']. ' seconds to send a request to '. $info['url']. "\n");
                    $return['result'] = 'OK';
                    $stuff = json_decode($result);
                    $return['response'] = $stuff;
                    return json_encode($return);
                }else{

                    Log::error('Failed getting '.$info['url'].' : response code '.$info['http_code']);
                }
            }
            return $return;           

        }
    }

    public function subscribeUser(Request $request)
    {
        /* implement Sendlane api 
         * returns json
         */
        Log::info('begin subscribe function');
        $return = array();
        $return['result'] = 'error';
	Log::info('request validated');
         $api_key = env('SENDLANE_API_KEY','6d507e5b8b5474d');
         $hash_key = env('SENDLANE_HASH_KEY','f7322a3aef2bb58d8aabf9f380e17d59');
	 $api_url = env('SENDLANE_API_URL','https://trafficroots.sendlane.com/api/v1/');

	 $pw = env('MAIL_PASSWORD');
	 Log::info("PW = $pw");
	 $pw = env('MAIL_PASSWORD','fuck');
	 Log::info("PW = $pw");
         $command = 'list-subscriber-add';
         $list_id = intval($request->list_id);
         $email = $request->email;
         $first_name = filter_var($request->first_name, FILTER_SANITIZE_STRING);
         $last_name = filter_var($request->last_name, FILTER_SANITIZE_STRING);;
         $url = $api_url.$command;
         $process = false;
 
         if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		 $process = true;
		 Log::info("Posting to $url");
	 }else{

            Log::error('Invalid subscribe attempt using '.$email);     
            $return['response'] = "Invalid email";  
         }
         if($process && $this->checkCache()){
		 $post = array('api' => $api_key, 'hash' => $hash_key, 'list_id' => $list_id, 'email' => $email, 'first_name' => $first_name, 'last_name' => $last_name);
		 Log::info(print_r($_ENV, true));
         $ch = curl_init();
         Log::info('cUrl initiated');
         curl_setopt($ch,CURLOPT_URL, $url);
         curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
         curl_setopt($ch, CURLOPT_POST, 1);
         curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
         $result = curl_exec($ch);
         if (!curl_errno($ch)) {
            $info = curl_getinfo($ch);
            if($info['http_code'] == 200){
                Log::info( 'Took '. $info['total_time']. ' seconds to send a request to '. $info['url']. "\n");
                $return['result'] = 'OK';
                $stuff = json_decode($result);
                $return['response'] = $stuff;
                return json_encode($return);
            }else{
                
                Log::error('Failed getting '.$info['url'].' : response code '.$info['http_code']);
            }
	 }else{
		 Log::error('Curl Error No: '.curl_errno($ch));
	 }
         } 
         return json_encode($return);
    }
}
