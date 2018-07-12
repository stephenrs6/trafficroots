<?php
/*
 * TrafficRoots Adserver
 * @author Cary White
 */

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Cache;
use Log;
use DB;
use Crypt;
use Redis;
use Cookie;
use App\Zone;
use App\Site;
use App\Ad;
use App\Bid;
use App\DefaultAd;
use App\Creative;
use App\Browser;
use App\OperatingSystem;
use App\Platform;
use App\Country;
use App\State;
use App\City;
use Illuminate\Http\Request;
use App\Http\Controllers\PixelController;
class AdserverController extends Controller
{
    public $visitor = array();
    public $handle = '';
    public $zone;
    public $ad_id;
    public $creative;
    public $cookie;
    public $keywords = array();
    public $campaign;
    public $debug = "Start\n";

    public function __construct()
    {

    }

    public function getIndex(Request $request)
    {
        $cookie = Cookie::get();
        $this->cookie = $cookie;
        if(isset($cookie['pixel'])){
            $this->visitor = unserialize($cookie['pixel']);
        }else{
            $pixel = new PixelController();
            $this->visitor = $pixel->getUser($request);
            setcookie('pixel',serialize($this->visitor),time() + 86400);
        }
        $this->debug .= "Visitor Info\n".print_r($this->visitor,true);
        if(isset($request->keywords)){
            $this->keywords = explode(" ",$request->keywords);
            $this->debug .= "Keywords\n".print_r($this->keywords,true);
        }
        $geos = array();
        $geo[] = 'US';
        $geo[] = 'CA';
        if(!in_array($this->visitor['geo'], $geo)) $this->showGeoAd(); 
        $this->handle = $request->handle;
        if(strlen($this->handle)){
            $this->zone = Cache::remember('zone_'.$this->handle, 60, function()
            {
                return Zone::where('handle', $this->handle)->first();
            });
            $this->debug .= "Got Zone\n";
            $this->readCookie();
            if($this->zone){
                $ads = Cache::remember('ads_'.$this->handle, 10, function()
                {
                    return Ad::where('zone_handle', $this->handle)->where('status', 1)->orderBy('weight', 'desc')->get();
                });
                $this->debug .= "Got Ads\n";
                if(sizeof($ads)){
                    //we have buyers
                    if(sizeof($ads) == 1){
                        $ad = $ads[0];
                    }else{
                        $ad = $this->runLottery($ads);
                    }
                    if(!$ad->buyer_id){
                        $this->debug .= "No fixed ads, falling to bidding\n";
                        $bid = $this->runBidLottery();
                        $this->debug .= "Bidding Winner is Campaign ".$bid->campaign_id."\n";
                        if(is_object($bid)){
                            $this->ad_id = 'BID_'.$bid->id;
                            $this->showBidAd($bid);
                        }else{
                           $this->debug .= "Really?\n"; 
                           $this->showDefaultAd();
                        }
                    }
                    $this->ad_id = 'AD_'.$ad->id;
                    $this->showAd($ad);
                }else{
                   
                   $this->showDefaultAd();
                }
            }    
        }
    }
    /**
     * read cookies
     * @author Cary White
     */
    public function readCookie()
    {
        $this->cookie = Cookie::get();
        $cookies = $this->cookie;
        $unique = true;
        foreach($cookies as $key => $val){
            if(substr($key,0,3) == "ad_"){
                $cookieval = json_decode(Cookie::get($key));
                $this->capped_ads[substr($key,3)] = $cookieval[0];
            }            
            if($key == $this->handle){
                $unique = false;
                $this->debug .= "You've visited this Zone already today\n";
            }
        }
        if($unique){
            $this->debug .= "This is your first visit\n";
            setcookie($this->handle,"unique",strtotime("+1 day"));
            $keyname = 'UNIQUE_'.$this->handle.'_'.date('Y-m-d');
            Redis::incr($keyname);
            $this->debug .= "Unique Visitor recorded on Zone ".$this->handle."\n";
        }

    }
    /**
     * @author Cary White
     * @returns array
     * @access public
     * returns array of advertisers and their current bank balance
     */
    public function getBuyerBalances()
    {
        $bank = Cache::remember('buyer_balances', 1, function()
        {
            $buyers = array();
            foreach(DB::select('SELECT a.user_id, a.running_balance FROM buyers.bank a JOIN (SELECT MAX(id) AS id, user_id FROM buyers.bank GROUP BY user_id) b ON a.id=b.id') as $buyer){
                 $buyers[$buyer->user_id] = $buyer->running_balance;
            }
            return $buyers;
        });
        return $bank;
    }
    public function runBidLottery()
    {
        $mybids = Cache::remember('bids_'.$this->handle, 10, function()
        {
            return Bid::where('zone_handle', $this->handle)->where('status', 1)->get();       
        });
        $bids = array();
        foreach($mybids as $bid){
            $bids[] = $bid;
        }
        /* perform targeting and capping */
        $bids = $this->shakeDown($bids);
        if(sizeof($bids)){
            $this->debug .= sizeof($bids)." Bidder(s) found\n";
            $weights = array();
            $default = 100 / sizeof($bids);
            $cash = 0;
            //participation weight
            foreach($bids as $bid){
                $weights[$bid->id] = $default;
                $cash += $bid->bid;
            }
            $this->debug .= "Cash = $cash\n";
            //contribution weight
            foreach($bids as $bid){
                $weights[$bid->id] += ($bid->bid / $cash) * 100;
            }
            //lottery
            $min = 200;
            foreach($weights as $k => $v){
                if($v < $min) $min = $v;
            }
            asort($weights);
            $rand = mt_rand($min,200);
            $this->debug .= "Random: $rand\nWeights:".print_r($weights,true)."\n";
            foreach($weights as $key => $value){
                if($value >= (200 -$rand)){
                    foreach($bids as $bid){
                        if($bid->id == $key) {
                            if($bid->frequency_capping){
                                /* set cookie */
                                $cookiename = 'ad_'.$bid->campaign_id;
                                if(isset($_COOKIE[$cookiename])){
                                    $thiscookie = json_decode($_COOKIE[$cookiename]);
                                    $views = intval($thiscookie[0]);
                                    $expires = intval($thiscookie[1]);
                                    $views = ($views + 1); 
                                    $thiscookie[0] = $views;
                                    setcookie($cookiename,json_encode($thiscookie),$expires);
                                    $this->debug .= "You've seen this ad $views times today\n";  
                                }else{
                                    /* set the cookie */
                                    $cookieval = array(1,strtotime("+1 day"));
                                    setcookie($cookiename,json_encode($cookieval),strtotime("+1 day"));
                                    $this->debug .= "This is the first time you've seen this ad today.\n";
                                }
                            }else{$this->debug .= "This campaign does not employ frequency capping\n";}
                            $this->debug .= "Returning Bid id ".$bid->id."\n";
                            return $bid;
                        }
                    }
                }
            }
            $this->showDefaultAd();        
         }else{
            $this->showDefaultAd();
        }
    }
    /**
     * function to target and frequency cap ads
     * @author Cary White
     */
    public function shakeDown($bids)
    {
        if(sizeof($bids)){
            /* get valid targets for this instance */
            /* platforms */
            $valid_platforms = array();
            $valid_platforms[] = 0;
            $platforms = Platform::all();
            foreach($platforms as $platform){
                if($this->visitor['platform'] == $platform->platform) $valid_platforms[] = $platform->id;
            }
            /* operating systems */
            $valid_os = array();
            $valid_os[] = 0;
            $os_targets = OperatingSystem::all();
            foreach($os_targets as $os){
                if($os->os == $this->visitor['os']) $valid_os[] = $os->id;
            }            
            /* browsers */
            $valid_browsers = array();
            $valid_browsers[] = 0;
            $browsers = Browser::all();
            foreach($browsers as $browser){
                if($this->visitor['browser'] == $browser->browser) $valid_browsers[] = $browser->id;
            }
            /* countries */
            $valid_geos = array();
            $valid_geos[] = 0;
            $countries = Country::all();
            foreach($countries as $country){
                if($this->visitor['geo'] == $country->country_short) $valid_geos[] = $country->id;
            }
            /* states */
            $valid_states = array();
            $valid_states[] = 0;
            $states = State::all();
            foreach($states as $state){
                if($this->visitor['state'] == $state->state_name) $valid_states[] = $state->state_name;
            }
            /* cities */
            $valid_cities = array();
            $valid_cities[] = 0;
            $cities = City::all();
            foreach($cities as $city){
                if($this->visitor['city'] == $city->city_name) $valid_cities[] = $city->city_name;
            } 

            /* process campaigns */
            $bank = $this->getBuyerBalances();
            foreach($bids as $key => $bid){
                /* check bank balances */
                if($bid->bid > (float) $bank[$bid->buyer_id]){
                    /* bid amount exceeds bank balance */
                    $this->debug .= "Bid Campaign ".$bid->campaign_id." was removed by bank checker.\n";
                    unset($bids[$key]);
                }
             }
            $bids = array_values($bids);
            foreach($bids as $key => $bid){
                /* frequency capping */
                if($bid->frequency_capping){
                    if(isset($_COOKIE['ad_'.$bid->campaign_id])){
                        $thiscookie = json_decode($_COOKIE['ad_'.$bid->campaign_id]);
                        $views = intval($thiscookie[0]);
                        if($views >= $bid->frequency_capping){
                            $this->debug .= "Bid Campaign ".$bid->campaign_id." was removed by Frequency Capper\n";
                            unset($bids[$key]);
                        }
                    }
                }
             }
             $bids = array_values($bids);
             foreach($bids as $key => $bid){ 
                /* platform targeting */
                if($bid->device_id == '0'){}else{
                    $include = false;
                    $platforms = explode("|",$bid->device_id);
                    foreach($platforms as $k2 => $v2){
                           if(in_array(intval($v2), $valid_platforms)){
                               $include = true; 
                               break;
                           }                        
                    }   
                    if(!$include) unset($bids[$key]);   
                }
              }
              $bids = array_values($bids);
              foreach($bids as $key => $bid){
                /* browser targeting */
                if($bid->browser_id == '0'){}else{
                    $include = false;
                    $browsers = explode("|",$bid->browser_id);
                    foreach($browsers as $k2 => $v2){
                           if(in_array(intval($v2), $valid_browsers)){
                               $include = true;
                               break;
                           }
                    }
                    if(!$include) unset($bids[$key]);
                }
              }
              $bids = array_values($bids); 
              foreach($bids as $key => $bid){
                /* os targeting */
                if($bid->os_id == '0'){}else{
                    $include = false;
                    $operating_systems = explode("|",$bid->os_id);
                    foreach($operating_systems as $k2 => $v2){
                           if(in_array(intval($v2), $valid_os)){
                               $include = true;
                               break;
                           }
                    }
                    if(!$include) unset($bids[$key]);
                }
              }
              $bids = array_values($bids);
              foreach($bids as $key => $bid){
                /* keyword targeting */
                if(strlen($bid->keywords)){
                    $include = false;
                    $this->debug .= "Keywords: ".$bid->keywords."\n";
                    $my_keywords = Array();
                    $my_keywords = explode("|",$bid->keywords);                          
                    foreach($my_keywords as $key2 => $value2) {
                        foreach($this->keywords as $key3 => $value3){
                            $pos = strpos(strtoupper($value3), strtoupper($value2));
                            if($pos === false){
                            } else {
                                $include = true;
                                $this->debug .= "Keyword matched!\n";
                                break;
                            }
                        }
                    }
                    if(!$include){
                        unset($bids[$key]);
                        $this->debug .= "Bid Campaign ".$bid->campaign_id." was removed by Keyword Targeter\n";
                    }                   
                }else{
                    $this->debug .= "Keyword targeting skipped\n";
                }
                /* end keyword targeting */
               }
               $bids = array_values($bids);
            
            return $bids;
        }else{
            return $bids;
        }

    }
    public function runCreativeLottery($creatives)
    {
        $weight = mt_rand(0,100);
        foreach($creatives as $creative){
            if($weight <= $creative->weight) return $creative;       
        }
        return false;
        
    }
    public function runLottery($ads)
    {
        $weight = mt_rand(0,100);
        foreach($ads as $ad){
            if($weight <= $ad->weight) return $ad;       
        }
        return false;
    }
    public function showDefaultAd()
    {
        $this->debug .= "Showing Default Ad\n";
        $ads = Cache::remember('default_ads_'.$this->handle, 10, function(){
             return DefaultAd::where('location_type', $this->zone->location_type)->get();
        });
        $winner = $ads[mt_rand(0,sizeof($ads)-1)];
        $this->ad_id = 'DEF_'.$winner->id;
        $this->recordDefaultImpression($winner);
        if($winner->folder_id){
            $this->returnIframe($winner->folder_id);
        }else{
            $this->returnDiv($winner->media_id, $winner->link_id);
        } 
        echo "\n\n".str_replace("\n", "<br />",$this->debug); 
    }
    public function returnDiv($media_id, $link_id)
    {
        $out = '<!DOCTYPE html>
<html>
<head>
<style type="text/css">
    body, html {
        margin-left: 0px;
        margin-top: 0px;
        margin-right: 0px;
        margin-bottom: 0px;
    }
    a img { border: none; }
</style>
</head>
<body>';
        $out .= '<div title="Advertisement" width="100%" height="100%">';
        $media = DB::select("select * from buyers.media where id = $media_id");
        $link = DB::select("select * from buyers.links where id = $link_id");
        $out .= '<a href="'.$this->prepareLink($link[0]->url).'"><img src="https://buyers.trafficroots.com/'.$media[0]->file_location.'"></a></div>';
        $out .= "<pre>".str_replace("\n","<br />",$this->debug)."</pre>";
        $out .= '</body>
                 </html>';
        die($out); 
    }
    public function returnIframe($folder_id)
    {
        $out = '<!DOCTYPE html>
<html>
<head>
<style type="text/css">
    body, html {
        margin-left: 0px;
        margin-top: 0px;
        margin-right: 0px;
        margin-bottom: 0px;
    }
    a img { border: none; }
</style>
</head>
<body>';
        $sql = 'select * from `buyers`.`folders` where id = '.$folder_id;
        //die(''.$sql);
        $folder = DB::select('select * from `buyers`.`folders` where id = '.$folder_id);
        if(!$folder) die('fuck...');
        $iframe = '<iframe frameborder="0" width="100%" height="100%" style="overflow: hidden; position: absolute; allowtransparency="true" style="border:0px; margin:0px;" src="https://buyers.trafficroots.com'
        .$folder[0]->file_location.'"></iframe>';
        $out .= $iframe;
        $out .= "<pre>".str_replace("\n","<br />",$this->debug)."</pre></body>\n</html>";
        die($out);
    }
    public function showGeoAd()
    {
        // todo: grab backfill ad
    }

    public function showBidAd($bid)
    {
        if(!is_object($bid)) die($this->debug);
        $this->campaign = $bid->campaign_id;
        $creatives = Cache::remember('bid_creatives_'.$bid->id, 10, function()
        {
            return DB::select("SELECT * FROM buyers.creatives WHERE campaign_id = ".$this->campaign
                               ." AND status = 1 ORDER BY weight DESC");
        });
        if(sizeof($creatives)){
            if(sizeof($creatives == 1)){
                $creative = $creatives[0];
            }else{
                $creative = $this->runCreativeLottery($creatives);
            }
            $this->debug .= "Got Creative(s)\n";
            $this->recordImpression($bid,$creative);
            $this->debug .= "Impression Recorded\n";
            $this->recordSale($bid);
            $this->debug .= "Sale recorded\n";
            $this->creative = $creative->id;
            if($creative->folder_id){
                $this->returnIframe($creative->folder_id);
            }else{
                $this->returnDiv($creative->media_id,$creative->link_id);
            }
        }else{
            Log::error('no creatives for bid '.$bid->id);
        }
    }
    public function prepareLink($url)
    {
        $return = "https://publishers.trafficroots.com/click/";
        $clickdata = array();
        $clickdata['url'] = $url;
        $clickdata['zone'] = $this->handle;
        $clickdata['campaign'] = $this->ad_id;
        $clickdata['creative'] = $this->creative;
        $link = Crypt::encrypt(serialize($clickdata));
        $return .= $link;
        $this->debug .= "Destination Link: ".Crypt::decrypt($link);
        return $return;
    }
    public function recordSale($bid)
    {
        $keyname = 'SALE|'.$bid->id.'|'.$bid->bid;
        Redis::sadd('KEYS_SALES', $keyname);
        Redis::incr($keyname);    
    }
    public function showAd($ad)
    {
        $creatives = Cache::remember('creatives_'.$ad->ad_id, 10, function()
        {
            return Creative::where('ad_id', $ad->id)->orderBy('weight', 'desc')->get();
        });
        if(sizeof($creatives)){
            if(sizeof($creatives == 1)){
                $creative = $creatives[0];
            }else{
                $creative = $this->runCreativeLottery($creatives);
            }
        }
        $this->recordImpression($ad,$creative);
        return view('ad',['creative' => $creative, 'ad' => $ad]);
    }
    public function recordImpression($ad, $creative)
    {
        $keyname = 'IMPRESSION|'.date('Y-m-d').'|'.$this->handle.'|'.$this->ad_id.'|'.$creative->id.'|'.serialize($this->visitor);
        Redis::sadd('KEYS_IMPRESSIONS', $keyname);
        Redis::incr($keyname);    
    }
    public function recordDefaultImpression($ad)
    {
        $keyname = 'DEFAULT|'.date('Y-m-d').'|'.$this->handle.'|'.$this->ad_id.'|'.serialize($this->visitor);
        Redis::sadd('KEYS_DEFAULT', $keyname);
        Redis::incr($keyname);
    }
    public function clickedMe(Request $request)
    {
        $cookie = Cookie::get();
        if(isset($cookie['pixel'])){
            $this->visitor = unserialize($cookie['pixel']);
        }else{
            $pixel = new PixelController();
            $this->visitor = $pixel->getUser($request);
        }
        $query = unserialize(Crypt::decrypt($request->querystr));
        $url = $query['url'];
        $ad_id = $query['campaign'];
        $creative = $query['creative'];
        $zone_id = $query['zone'];

        $key = 'CLICK|'.date('Y-m-d').'|'.$ad_id.'|'.$creative.'|'.$zone_id.'|'.serialize($this->visitor);
        Redis::sadd('KEYS_CLICKS', $key);
        Redis::incr($key);
                
        return redirect($url);
    }
}
