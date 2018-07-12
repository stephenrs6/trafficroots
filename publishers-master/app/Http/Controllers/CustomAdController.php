<?php

namespace App\Http\Controllers;

use App\Ad;
use DB;
use Log;
use Illuminate\Http\Request;

class CustomAdController extends Controller
{
	public function __construct(){}

	public function checkAdSchedule()
	{
		/* to start and stop custom ads that have dates */

		/* stop ads that are running on the specified date */
		$ads = Ad::where('status', 1)->where('user_id', '>', 0)->where('end_date', '<=', date('Y-m-d'))->get();
		if(sizeof($ads)){
			foreach($ads as $ad){
				$rtb = Ad::where('zone_handle', $ad->zone_handle)->where('user_id', 0)->first();
				if($rtb){
					$weight = $rtb->weight + $ad->weight;
					if($weight > 100) $weight = 100;
					$rtb->update(array('weight' => $weight));
					$ad->update(array('status' => 2));
					Log::info('Disabled publisher ad '.$ad->id.' on zone '.$ad->zone_handle);
				}
			}
		}
                /* start ads that are pending */
		$ads = Ad::where('status', 5)->where('user_id', '>', 0)->where('start_date', '<=', date('Y-m-d'))->get();
		if(sizeof($ads)){
			foreach($ads as $ad){
				if($this->checkWeight($ad->zone_handle)){
				    $rtb = Ad::where('zone_handle', $ad->zone_handle)->where('user_id', 0)->first();
				    if($rtb){
					$weight = $rtb->weight - $ad->weight;
					if($weight < 0) $weight = 0;
					$rtb->update(array('weight' => $weight));
					$ad->update(array('status' => 1));
					Log::info('Started publisher ad '.$ad->id.' on zone '.$ad->zone_handle);
				    }
				}
			}
		}


	}

	public function impressionCapper()
	{
		/* stop ads that are impression capped */
		$ads = Ad::where('status', 1)->where('buyer_id', '>', 0)->where('impression_cap', '>', 0)->get();
		Log::info('Impression Capper Started - '.sizeof($ads).' Ads to process.');
		if(sizeof($ads)){
			foreach($ads as $ad){
		            $sql = "SELECT SUM(impressions) AS impressions FROM pub_stats WHERE ad_id = ?";
			    $result = DB::select($sql,array($ad->id));
			    if($result[0]->impressions >= $ad->impression_cap){
				$rtb = Ad::where('zone_handle', $ad->zone_handle)->where('buyer_id', 0)->first();
				if($rtb){
					$weight = $rtb->weight + $ad->weight;
					if($weight > 100) $weight = 100;
					$rtb->update(array('weight' => $weight));
					$ad->update(array('status' => 2));
					Log::info('Disabled publisher ad '.$ad->id.' on zone '.$ad->zone_handle.' after '.$result[0]->impressions.' impressions.');
				}
			    }
			}
		}
                Log::info('Impression Capper finished.');		
	}

	public function checkWeight($zone)
	{

		$sql = "SELECT SUM(weight) as weight
			FROM ads 
                        WHERE zone_handle = ?
                        AND status = ?";
                $result = DB::select($sql, array($zone,1));
                if($result[0]->weight == 100){
                    return true;
                }
                return false;
        }
}
