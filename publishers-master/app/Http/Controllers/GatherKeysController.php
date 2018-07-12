<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Cache;
use DB;
use Log;
use App\Zone;
use App\DefaultAd;
use App\Campaign;
use App\Bid;
use App\Site;
use App\Browser;
use App\OperatingSystem;
use App\Platform;
use App\Country;
use App\City;
use App\State;
use App\User;
use App\Bank;
use App\ServiceLevel;
use DOMDocument;
use DOMXPath;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmUser;
use App\Http\Controllers\CustomAdController;
class GatherKeysController extends Controller
{
    public $impressions = 0;

    public function __construct()
    {

    }
    public function getIndex()
    {
        $this->gatherSiteKeys();
        $this->gatherImpressions();
	$this->gatherDefaultImpressions();
	$this->recordServiceLevel();
        $this->gatherClicks();
	$this->gatherSales();
	$this->enforceBudgets();
	$this->findCountyByZip();
	//$this->populateZipsTable();
	$this->sendUserConfirmation();
	$this->bounceUsers();
	$this->bankSetup();
	$this->populateSpend();
	$this->populateZoneStats();
	$this->populateCampaignStats();

        $customAd = new CustomAdController();
	$customAd->impressionCapper();
	//$this->updateAllZoneStats();
    }
    public function bankSetup()
    {
	    /* make sure all users have a bank entry */
	    $sql = 'SELECT users.* FROM users
		    LEFT OUTER JOIN bank
                    ON users.id = bank.user_id
                    WHERE bank.user_id IS NULL;';
            $result = DB::select($sql);
            if(sizeof($result)){
		    Log::info('Found ' . sizeof($result) . ' users who need bank setup');
		    foreach ($result as $row){
                        $data = array('user_id' => $row->id, 'transaction_amount' => 0.00, 'running_balance' => 0.00, 'created_at' => date('Y-m-d H:i:s'));
			$newbank = new Bank();
			$newbank->fill($data);
			$newbank->save();
                        Log::info('Created initial bank entry for ' . $row->name); 
		    }
		    Log::info('Completed Bank Setup');
	    }

    }
    public function recordServiceLevel()
    {
	    $data = array('impressions' => $this->impressions);
	    $row = new ServiceLevel();
	    $row->fill($data);
	    $row->save();
	    Log::info('Service Level Recorded: '.$this->impressions.' impressions per minute');
    }
    public function gatherDefaultImpressions()
    {
         // 'DEFAULT|'.date('Y-m-d').'|'.$this->handle.'|'.$this->ad_id.'|'.serialize($this->visitor);
        /* get impression keys */
	    $pairs = array();
	    try{
		    /*    
		    $handle = fopen("/var/www/publishers/storage/logs/laravel.bak", "r");
		    if ($handle) {
			    Log::info('Opened Log File!');
			        while (($line = fgets($handle)) !== false) {
					if(strpos($line, 'Deleted Default')){
                                            $keyname = substr($line, strpos($line, 'DEFAULT|'));
					    Log::info('Need to add back '.$keyname);
					    Redis::sadd('KEYS_DEFAULT', $keyname);
					    Redis::incr($keyname);
					   
					}	
			         }
			             fclose($handle);
		    } else {
			    Log::error('Cannot open log file');
			             // error opening the file.
		    } 
		     */
        do{
            $keyname = Redis::spop('KEYS_DEFAULT');
            if(strlen(trim($keyname))){
                $val = Redis::get($keyname);
                Redis::del($keyname);
                Log::info('Deleted Default KEY - '.$keyname);
                $stuff = explode("|", $keyname);
                $visitor = unserialize($stuff[4]);
                $zone = Zone::where('handle', $stuff[2])->first();
                $ad_info = explode('_', $stuff[3]);
                if(is_array($ad_info) && intval($ad_info[1])){
			$ad = DefaultAd::where('id', intval($ad_info[1]))->get();
			if(!sizeof($ad)){
				Log::error('Default Ad '.$ad_info[1].' not found!');
				continue;
			}
                }else{
                    Log::info('WTF, people?');
                }
		$data = $this->transposeUser($visitor);
		if(sizeof($ad)){
                $pair = '('.$ad[0]->affiliate_id.','.$zone->site_id.','
                        .$zone->id.","
                        .intval($ad_info[1]).","
                        .$data['country'].','
                        .$data['state'].','
                        .$data['city'].','
                        .$data['platform'].','
                        .$data['os'].','
                        .$data['browser'].','
                        .$val.",'"
                        .$stuff[1]."')";
		$pairs[] = $pair;
		$this->impressions += $val;
		}
            }
	}while(!$keyname == '');

        }catch(Exception $e){
            Log::error($e->getMessage());
	    Log::error($e->getFile());
	    Log::error($e->getLine());
	}
        if(sizeof($pairs)){
        $prefix = "INSERT INTO affiliate_stats (`affiliate_id`,`site_id`,`zone_id`,`ad_id`,`country_id`,`state_code`,`city_code`,`platform`,`os`,`browser`,`impressions`,`stat_date`) VALUES";
        $suffix = " ON DUPLICATE KEY UPDATE `impressions` = `impressions` + VALUES(`impressions`);";
        $query = $prefix.implode(",",$pairs).$suffix;
        DB::insert($query);
        //Log::info($query);
	}
        Log::info('Default Impressions Gathered');       
    }
    public function gatherSiteKeys()
    {
        Log::info('Gather Site Keys began');
        /* get browser definitions */
        $browsers = array();
        $browsers[0] = 'Other';
        $b = Browser::all();
        foreach($b as $row){
           $browsers[$row->id] = $row->browser;
        }
        /* get platform definitions */
        $platforms = array();
        $platforms[0] = 'Other';
        $p = Platform::all();
        foreach($p as $row){
            $platforms[$row->id] = $row->platform;
        }
        /* get os definitions */
        $os = array();
        $os[0] = 'Other';
        $o = OperatingSystem::all();
        foreach($o as $row){
            $os[$row->id] = $row->os;
        }
        
        $str = 'laravel:SITE|*';
        $keys = Redis::keys($str);
        foreach($keys as $key => $value){
            $mydata = explode('|',$value);
            $site = Site::where('site_handle', $mydata[1])->first();
               
            $impressions = Redis::getset($value, 0);
            if($impressions){
            //Log::info($site['site_name'].' - handle: '.$mydata[1].' Key: '.$value.' has a value of '.$impressions);
            $sql = "INSERT INTO site_analysis (site_handle, stat_date, geo, state, city, device, browser, os, impressions)
                    VALUES('".$mydata[1]."','".$mydata[2]."','".$mydata[3]."','".addslashes($mydata[4])."','".addslashes($mydata[5])."',";
            $device = 0;
            foreach($platforms as $k => $v){
                if($mydata[6] == $v) {
                    $device = $k;
                }
            }    
            $browser = 0;
            foreach($browsers as $k => $v){
                if($mydata[8] == $v){
                    $browser = $k;
                }
            }
            $myos = 0;
            foreach($os as $k => $v){
                if($mydata[7] == $v){
                    $myos = $k;
                }
            }
            $sql .= "$device,$browser,$myos, $impressions) ON DUPLICATE KEY UPDATE impressions = impressions + $impressions";
            DB::insert($sql);
            }else{
                Redis::del($value);
            }
                   
        }





        Log::info('Gather Site Keys ended.');        

    }
    public function transposeUser($user)
    {
	try{
        $data = array();
        if(is_array($user)){
            $platform = Platform::where('platform', $user['platform'])->get();
            $browser = isset($user['browser']) ? Browser::where('browser', $user['browser'])->get() : 0;
            $os = OperatingSystem::where('os', $user['os'])->get();
            $country = Country::where('country_short', addslashes($user['geo']))->get();
            if(!sizeof($country) && strlen($user['geo'])){
                DB::insert("INSERT INTO countries VALUES(NULL,'".$user['geo']."','',NULL,NULL);");
                $country = Country::where('country_short', $user['geo'])->get();
            }
	    $state = State::where('state_name', addslashes($user['state']))->where('country_id', $country[0]->id)->get();
	    if(!sizeof($state) && strlen($user['state'])){
                DB::insert('INSERT INTO states (`state_name`, `country_id`, `legal`) VALUES(?,?,?) ON DUPLICATE KEY UPDATE state_name = VALUES(`state_name`)', array(addslashes($user['state']),$country[0]->id,0));
                $state = State::where('country_id', $country[0]->id)->where('state_name', addslashes($user['state']))->get();
	    

                $city = City::where('state_code', $state[0]->id)->where('city_name', addslashes($user['city']))->get();
                if(!sizeof($city) && strlen($user['city'])){
                    DB::insert("INSERT INTO cities VALUES(NULL,'".addslashes($user['city'])."',".$state[0]->id.",NULL,NULL);");
                    $city = City::where('state_code', $state[0]->id)->where('city_name', $user['city'])->get();
	        }
	    }else{
		if(sizeof($state)){    
                    $city = City::where('state_code', $state[0]->id)->where('city_name', addslashes($user['city']))->get();
                    if(!sizeof($city) && strlen($user['city'])){
                        DB::insert("INSERT INTO cities VALUES(NULL,'".addslashes($user['city'])."',".$state[0]->id.",NULL,NULL);");
                        $city = City::where('state_code', $state[0]->id)->where('city_name', addslashes($user['city']))->get();
		    }
		}
	    }
            $data['platform'] = isset($platform[0]->id) ? $platform[0]->id : 0;
            $data['browser'] = isset($browser[0]->id) ? $browser[0]->id : 0;
            $data['os'] = isset($os[0]->id) ? $os[0]->id : 0;
            $data['country'] = isset($country[0]->id) ? $country[0]->id : 0;
            $data['state'] = isset($state[0]->id) ? $state[0]->id : 0;
            $data['city'] = isset($city[0]->id) ? $city[0]->id : 0;

        }
        return $data;
        }catch(Throwable $t){
		Log::error($t->getMessage());
		Log::error($t->getFile());
		Log::error($t->getLine());
	}
    }
    public function gatherSales()
    {
        Log::info("Processing bid sales keys");
        $count = 0;
        do{
            $keyname = Redis::spop('KEYS_SALES'); 
            //Log::info("Keyname: $keyname");  
            // 'SALE|'.$bid->campaign_type.'|'.$bid->id.'|'.$bid->bid
            $val = Redis::get($keyname);
            Redis::del($keyname);
            $stuff = explode("|", $keyname);
            if(count($stuff) > 4){
                $sale = 0;
                if($stuff[2] == 1){
                    /* CPM sale */
                    $sale = ($val / 1000) * floatval($stuff[4]);
                    //Log::info("Sale Amount: $".$sale);
                }
                if($stuff[2] == 2){
                    /* CPC sale */
                    $sale = $val * floatval($stuff[4]);
                    //Log::info("Sale Amount: $".$sale);
                }
                $user_id = $stuff[1];
                if($sale) $this->processBankTransaction($sale, $user_id);
            }else{
                Log::info("Invalid Key");
            }     
            $count += 1;       
        }while(!$keyname == '');
        Log::info("gatherSales completed processing $count keys");
    }
    public function populateSpend($date = '')
    {
	    if($date == '') $date = date('Y-m-d');
	
		
	Log::info('Beginning Spend Population');
        $buyers = DB::select("SELECT distinct(user_id) FROM bank WHERE created_at LIKE '$date%' AND transaction_amount < 0");
	foreach($buyers as $buyer)
	{
            $spend = DB::select("SELECT SUM(transaction_amount) AS spent FROM bank WHERE user_id = ? AND created_at LIKE '$date%' AND transaction_amount < 0;", array($buyer->user_id));
	    $spent = $spend[0]->spent;
	    $data = array($buyer->user_id, $date, $spent, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'));
	    DB::insert("INSERT INTO spend (user_id,spend_date,spent,created_at,updated_at) VALUES(?,?,?,?,?) ON DUPLICATE KEY UPDATE spent = $spent, updated_at = NOW();",$data);
	}
        Log::info("Processed $date");
	

        Log::info('Spend Population Completed!');
    }
    public function populateZoneStats($date = '')
    {
        $pairs = array();	
	    if($date == '') $date = date('Y-m-d');
	Log::info('Running Zone Stats for '.$date);
	DB::statement("SET sql_mode='';");
        $sql = "SELECT SUM(stats.impressions) impressions, SUM(stats.clicks) clicks, stats.zone_id, zones.pub_id, zones.site_id, zones.handle
		FROM stats
		JOIN zones
		ON stats.zone_id = zones.id
                WHERE stats.stat_date = ?
                GROUP BY stats.zone_id;";
        $zones = DB::select($sql,array($date));
        foreach($zones as $zone){
	    $data = array('user_id' => $zone->pub_id, 
		      'site_id' => $zone->site_id, 
		      'zone_id' => $zone->zone_id, 
		      'handle' => $zone->handle, 
		      'clicks' => $zone->clicks, 
		      'impressions' => $zone->impressions);
            $sql = "SELECT SUM(impressions) impressions, SUM(clicks) clicks, zones.site_id, zones.pub_id, zones.handle
                    FROM affiliate_stats 
                    JOIN zones ON affiliate_stats.zone_id = zones.id
		    WHERE zone_id = ? AND stat_date = ?";
            $result = DB::select($sql, array($zone->zone_id,$date));
            if(sizeof($result)){
                $data['impressions'] += $result[0]->impressions;
		$data['clicks'] += $result[0]->clicks;
	    }
	    $pairs[] = "(".$data['user_id'].",".$data['site_id'].",".$data['zone_id'].",'".$data['handle']."','$date',".$data['impressions'].",".$data['clicks'].",NOW())";
	}
	    $prefix = "INSERT INTO zone_stats (user_id,site_id,zone_id,handle,stat_date,impressions,clicks,created_at) VALUES";
	$suffix = " ON DUPLICATE KEY UPDATE impressions = VALUES(impressions), clicks = VALUES(clicks), updated_at = NOW();";
	$sql = $prefix . implode(",", $pairs) . $suffix;
             DB::statement($sql);
	Log::info('Zone Stats for '.$date.' completed!');
    }
    public function updateAllZoneStats()
    {
        $sql = "SELECT DISTINCT(stat_date) stat_date FROM stats ORDER BY stat_date";
        $dates = DB::select($sql);
        foreach($dates as $row){
	    Log::info('Processing Zone Stats for '.$row->stat_date);
            $this->populateZoneStats($row->stat_date);	    
            Log::info('Done');
            Log::info('Processing Campaign Stats for '.$row->stat_date);
	    $this->populateCampaignStats($row->stat_date);
	    Log::info('Done');
	}

    }
    public function populateCampaignStats($date = '')
    {
	    $pairs = array();
            DB::statement("SET sql_mode = '';");	    
	    if($date == '') $date = date('Y-m-d');
	Log::info('Running Campaign Stats for '.$date);
        $sql = "SELECT SUM(stats.impressions) impressions, SUM(stats.clicks) clicks, bids.buyer_id, bids.campaign_id
		FROM stats
		JOIN bids
		ON stats.bid_id = bids.id
                WHERE stats.stat_date = ?
                GROUP BY bids.campaign_id;";
        $campaigns = DB::select($sql,array($date));
        foreach($campaigns as $data){
	    $pairs[] = "(".$data->buyer_id.",".$data->campaign_id.",'$date',".$data->impressions.",".$data->clicks.",NOW())";
	}
	if(sizeof($pairs)){
	    $prefix = "INSERT INTO campaign_stats (user_id,campaign_id,stat_date,impressions,clicks,created_at) VALUES";
	    $suffix = " ON DUPLICATE KEY UPDATE impressions = VALUES(impressions), clicks = VALUES(clicks), updated_at = NOW();";
	    $sql = $prefix . implode(",", $pairs) . $suffix;
	    DB::statement($sql);
	}
	Log::info('Campaign Stats for '.$date.' completed!');
    }
    public function processBankTransaction($amount, $user_id)
    {
        if($amount && $user_id){
            /* get current balance */
            $sql = "SELECT * 
                    FROM bank
                    WHERE user_id = $user_id
                    ORDER BY id DESC 
                    LIMIT 1";
            $result = DB::select($sql);
            if(count($result)){
                $new_balance = $result[0]->running_balance - $amount;
                $transaction_amount = $amount * -1;
                $sql = "INSERT INTO bank (user_id, transaction_amount, running_balance, created_at, updated_at)
                        VALUES($user_id, $transaction_amount, $new_balance, NOW(), NOW())";
                DB::insert($sql);

            }

        }
    }
    public function gatherImpressions()
    {
        // 'IMPRESSION|'.date('Y-m-d').'|'.$this->handle.'|'.$this->ad_id.'|'.$this->bid_id.'|'.$creative->id.'|'.$ad->bid.'|'.serialize($this->visitor);
        /* get impression keys */
        $pairs = array();
        do{
            $keyname = Redis::spop('KEYS_IMPRESSIONS');
            if(strlen(trim($keyname))){
                $val = Redis::get($keyname);
                Redis::del($keyname);
                $stuff = explode("|", $keyname);
                $visitor = unserialize($stuff[7]);
                $zone = Zone::where('handle', $stuff[2])->first();
          
                $data = $this->transposeUser($visitor);
                $pair = '('.$zone->site_id.','
                        .$zone->id.","
                        .$stuff[3].","
                        .$stuff[4].','
                        .$stuff[5].','
                        .$stuff[6].','
                        .$data['country'].','
                        .$data['state'].','
                        .$data['city'].','
                        .$data['platform'].','
                        .$data['os'].','
                        .$data['browser'].','
                        .$val.",'"
                        .$stuff[1]."')";
		$pairs[] = $pair;
		$this->impressions += $val;
            }
        }while(!$keyname == '');
        if(sizeof($pairs)){
        $prefix = "INSERT INTO stats (`site_id`,`zone_id`,`ad_id`,`bid_id`,`ad_creative_id`,`cpm`,
                   `country_id`,`state_code`,`city_code`,`platform`,`os`,`browser`,`impressions`,`stat_date`) VALUES";
        $suffix = " ON DUPLICATE KEY UPDATE `impressions` = `impressions` + VALUES(`impressions`);";
        $query = $prefix.implode(",",$pairs).$suffix;
        DB::insert($query);
        //Log::info($query);
        }
        Log::info('Impressions Gathered');
    }
    public function gatherClicks()
    {
        // 'CLICK|'.date('Y-m-d').'|'.$zone_id.'|'.$ad_id.'|'.$bid_id.'|'.$creative.'|'.serialize($this->visitor);
	    $pairs = array();
	    $defs = array();
        do{
            $keyname = Redis::spop('KEYS_CLICKS');
            //Log::info($keyname);
            if(strlen(trim($keyname))){
                $val = Redis::get($keyname);
                Redis::del($keyname);
                $stuff = explode("|", $keyname);
                $visitor = unserialize($stuff[6]);
                $zone = Zone::where('handle', $stuff[2])->first();
                if($zone){
			$data = $this->transposeUser($visitor);
			if(substr($stuff[3],0,3) == 'DEF'){
				/* it's a default ad */
				$ad_info = explode('_', $stuff[3]);
				if(is_array($ad_info) && isset($ad_info[1])){
					$ad_id = intval($ad_info[1]);
					$ad = DefaultAd::where('id', $ad_id)->first();
                                        $pair = '('.$ad->affiliate_id.','.$zone->site_id.','
                                        .$zone->id.","
                                        .$ad_id.","
                                        .$data['country'].','
                                        .$data['state'].','
                                        .$data['city'].','
                                        .$data['platform'].','
                                        .$data['os'].','
                                        .$data['browser'].','
                                        .$val.",'"
                                        .$stuff[1]."')";
					$defs[] = $pair;
					Log::info($pair);
				} else {
					Log::info('not right...');
			        }
			}else{
                        $pair = '('.$zone->site_id.','
                        .$zone->id.","
                        .$stuff[3].","
                        .$stuff[4].','
                        .$stuff[5].','
                        .$data['country'].','
                        .$data['state'].','
                        .$data['city'].','
                        .$data['platform'].','
                        .$data['os'].','
                        .$data['browser'].','
                        .$val.",'"
                        .$stuff[1]."')";
                        $pairs[] = $pair;
			}
                 }else{
                     Log::error('Zone Handle '.$stuff[2].' not found!');
                 }
            }
        }while(!$keyname == '');
        if(sizeof($pairs)){
        $prefix = "INSERT INTO stats (`site_id`,`zone_id`,`ad_id`,`bid_id`,`ad_creative_id`,
                   `country_id`,`state_code`,`city_code`,`platform`,`os`,`browser`,`clicks`,`stat_date`) VALUES";
        $suffix = " ON DUPLICATE KEY UPDATE `clicks` = `clicks` + VALUES(`clicks`);";
        $query = $prefix.implode(",",$pairs).$suffix;
        DB::insert($query);
        //Log::info($query);
        }

        if(sizeof($defs)){
             $prefix = "INSERT INTO affiliate_stats (`affiliate_id`,`site_id`,`zone_id`,`ad_id`,`country_id`,`state_code`,`city_code`,`platform`,`os`,`browser`,`clicks`,`stat_date`) VALUES";
             $suffix = " ON DUPLICATE KEY UPDATE `clicks` = `clicks` + VALUES(`clicks`);";
             $query = $prefix . implode(",", $defs) . $suffix;
             DB::insert($query);
             Log::info($query);
	}else{
	     Log::info('no defs...');
	}
        $keys = Redis::keys('CLICK*');
        foreach($keys as $key => $val){
            Redis::del($val);
            Log::info('Deleted stray click key: '.$val);
        }
        Log::info('Clicks Gathered');

    }
    public function enforceBudgets()
    {
	 Log::info('Checking Budgets...');   
	 DB::statement("SET sql_mode = ''");
         /* get active campaigns with budgets */
	    $sql = "SELECT SUM(stats.impressions) as impressions, 
		    SUM(stats.clicks) as clicks, 
                    stats.cpm,
                    bids.id as bid_id,  
                    campaigns.id as campaign, 
		    campaigns.campaign_type,
		    campaigns.campaign_name,
                    campaigns.user_id,
		    campaigns.daily_budget,
                    campaigns.status
                    FROM stats
                    JOIN bids ON stats.bid_id = bids.id
                    JOIN campaigns ON bids.campaign_id = campaigns.id
		    WHERE stats.stat_date = CURDATE()
                    AND campaigns.status IN (1,8)
                    AND campaigns.daily_budget > 0
		    GROUP BY cpm;";
            $counter = 0;
	    foreach(DB::select($sql) as $row){
                   $counter++;
                   $spend = 0;
                   /* get today's spend for this campaign */ 
                   if($row->campaign_type == 1){
		       $spend = $row->cpm * ($row->impressions / 1000);
	           }
		   if($row->campaign_type == 2){
                       $spend = $row->cpm * $row->clicks;
		   }
		   if($row->status == 1){
			   /* enforce budget */
			   Log::info('User '.$row->user_id.' - Campaign '.$row->campaign.': '.$row->campaign_name.' has spent '.$spend.' of $'.$row->daily_budget);
		   if($spend >= $row->daily_budget){
                       /* budget exceeded */
			   $this->suspendCampaign($row->campaign);
		   }
		   }
		   if($row->status == 8){
                       /* check for budget increase */
		       if($row->daily_budget > $spend){
			       /* budget increased, re-activate campaign */
			       $this->activateCampaign($row->campaign);
		       }   
	           }
	    }   
	    Log::info("Checked $counter budgeted campaigns");
	    if(intval(date('H')) == 0 && intval(date('i')) == 1){
		    $this->reactivateSuspendedCampaigns();
            }else{
		    Log::info("It's ".date('H:i A').' UTC');
	    }
        
    }
    private function suspendCampaign($campaign)
    {
	    Log::info('Suspending Campaign '.$campaign.' - daily budget reached.');
	    $update = array('status' => 8);
	    Campaign::where('id', $campaign)->update($update);
	    Bid::where('campaign_id', $campaign)->update($update);
    }

    private function activateCampaign($campaign)
    {
            Log::info('Activating Campaign '.$campaign.' - daily budget increased.');
            $update = array('status' => 1);
            Campaign::where('id', $campaign)->update($update);
            Bid::where('campaign_id', $campaign)->update($update);
    }
    
    private function reactivateSuspendedCampaigns()
    {
	    /* reset all campaigns - should only run at midnight */
	    
	$update = array('status' => 1);
	Campaign::where('status', 8)->update($update);
	Bid::where('status', 8)->update($update);
    }
    public function findCountyByZip()
    {
	do{    
            $zip = Redis::spop('COUNTY_LOOKUP');
	    $this->lookupZip($zip);
          }while(!$zip == '');	    
    }
    public function lookupZip($zip)
    {
        $innerHTML = '';
        if(strlen($zip) == 5){
        Log::info('Looking up county by zip');
        Log::info($zip);
        $url = 'http://www.uscounties.com/zipcodes/search.pl?query='.$zip.'&stpos=0&stype=AND';
        $ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	$output = curl_exec($ch); 
	curl_close($ch);
	$doc = new DOMDocument();
	libxml_use_internal_errors(true);
	if(strlen($output)){
        $doc->loadHTML($output);
        $classname = 'results';
	$finder = new DomXPath($doc);
	$nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
	$tmp_dom = new DOMDocument(); 
	foreach ($nodes as $node) 
	{
	    $tmp_dom->appendChild($tmp_dom->importNode($node,true));
	}
	$innerHTML.=trim($tmp_dom->saveHTML()); 
	Log::info($innerHTML);
	//Log::info('ok');
	$x = 0;
	foreach($tmp_dom->getElementsByTagName('td') as $element){
		if($x == 2) {
			$county = trim($element->textContent);
		}
		if($x == 3){
	                $state = trim($element->textContent);
		        break;
		}
	        $x = $x + 1;
	}
	if(!isset($county)) return false;
	$key = $zip."_COUNTY";
	Redis::sadd('US_COUNTIES', $key);
	Redis::set($key, $county);
        Log::info('Redis key '.$key.' set to '.$county);
	$sql = 'SELECT *
		FROM trafficroots.zips
	        WHERE zip = ?;';
        $result = DB::select($sql, array($zip));
        if(!sizeof($result)){
	    $sql = 'SELECT * FROM trafficroots.states WHERE country_id = ? AND state_name = ?';
	    $states = DB::select($sql, array(840,$state));
	    if(sizeof($states) && $county){
		$state_id = $states[0]->id;
		$data= array('zip' => $zip, 'county' => $county, 'state_code' => $state_id);
		DB::table('trafficroots.zips')->insert($data);
	        Log::info('Zips table updated');
	     }
	}else{
                Log::info('Zip is known to us.');
	}
	
	return true;
	}
        }
               return false;
    }
    public function populateZipsTable()
    {
            $result = Redis::smembers("US_COUNTIES");
            foreach($result as $row){
                   $stuff = explode("_", $row);
      	           $zip = $stuff[0];
                   $this->lookupZip($zip);
                   sleep(.5);
            }

    }
    
    
    /* send confirmation email with redis token */
    public function sendUserConfirmation(){
	    $x = 0;
	    Log::info('Sending user confirmation emails...');
	    foreach(User::where('status', 0)->where('token_expires', 0)->get() as $user){
	        Log::info('Sending confirmation email to '.$user->name);
	        $handle = bin2hex(random_bytes(8));
		Redis::setex($handle, 86400 * 2, $user->id);
		try{
		Mail::to($user->email)->send(new ConfirmUser($user, $handle));
		Log::info('Mail Sent!');
		User::where('id', $user->id)->update(array('token_expires' => 1, 'updated_at' => date('Y-m-d H:i:s')));
		}catch(Exception $e){
                    Log::error($e->getMessage());
		}
		$x ++;
		if($x > 40)
		break;
            }
    }
    /* check for bounced user emails */
    public function bounceUsers() 
    {
	    Log::info('Checking bounces');
	    $connect_to = '{imap.gmail.com:993/imap/ssl}bounced';
	    $user_email = 'admin@trafficroots.com';
	    $pass = 'iysayjcymwihmfed';

	    try{$inbox = imap_open($connect_to, $user_email, $pass);}catch(Exception $e){ Log::info(print_r(imap_errors(), true)); }
	    if($inbox){
		    /* grab emails */
		    $emails = imap_search($inbox,'ALL');

		    /* if emails are returned, cycle through each... */
		    if($emails) {
			
			/* begin output var */
			$output = '';
	    	
		/* put the newest emails on top */
		rsort($emails);
                $x = 0;		
		/* for every email... */
		foreach($emails as $email_number) {
			
			/* get information specific to this email */
			$overview = imap_fetch_overview($inbox,$email_number,0);
			Log::info(print_r($overview,true));
			$header = explode("\n", imap_fetchheader($inbox, $email_number));
			Log::info(print_r($header, true));
			foreach($header as $line){
				$line = preg_replace( "/\r|\n/", "", $line );
				if(strpos($line, 'X-Failed-Recipients:') === 0) {
					$stuff = explode(' ', $line);
					Log::info(print_r($stuff, true));
                                        $sql = "DELETE FROM `trafficroots`.`users` WHERE `email` = '".$stuff[1]."' LIMIT 1";
					if(DB::delete($sql)) {
						Log::info('User '.$stuff[1].' removed');
				        }else{
				            Log::info('WTF??   '.$sql);
			                }		    
			        }
			}

		        imap_delete($inbox, $email_number);
			$x ++;
			if($x > 50) break;
		}
                   imap_close($inbox);	
		    } 		
	    
	    }else{
		    Log::error("Can't connect to '$connect_to': " . imap_last_error());
		    Log::info(print_r(imap_errors(), true));
	    }
            

    }
}
