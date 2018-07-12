<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\CommissionTier;
use App\Stats;
use App\PublisherBooking;
use Log;

class EarningsController extends Controller
{
    /* process stats by date and calculate publisher/company earnings */
    /* @author Cary White */
    
    public $date;
    
    public function __construct($date)
    {
        $this->date = $date;
    }   

    public function processEarnings()
    {
        $mydate = $this->date;
        Log::info("\n\nProcessEarnings began for $mydate");
        $zones = array();
        $impressions = array();
        $clicks = array();
        $commission_tier = 1;
        $sql = "SELECT SUM(stats.impressions) as impressions, 
SUM(stats.clicks) as clicks, 
stats.cpm, 
stats.zone_id,
stats.site_id,
zones.pub_id, 
stats.bid_id, 
bids.campaign_id, 
campaigns.campaign_type
FROM stats
JOIN zones on stats.zone_id = zones.id
JOIN bids ON stats.bid_id = bids.id
JOIN campaigns ON bids.campaign_id = campaigns.id
WHERE stats.stat_date = '$mydate'
GROUP BY site_id, zone_id, pub_id, bid_id, cpm;";
        $result = DB::select($sql);
        //Log::info("Result selected".count($result)." rows");
        foreach($result as $row){
            $earnings = 0;
            if($row->campaign_type == 1){
                $earnings = $row->cpm * ($row->impressions / 1000);
            }
            if($row->campaign_type == 2){
                $earnings = $row->cpm * $row->clicks;
            }
            $zones[$row->zone_id] = isset($zones[$row->zone_id]) ? ($zones[$row->zone_id] + $earnings) : $earnings;
            $impressions[$row->zone_id] = isset($impressions[$row->zone_id]) ? ($impressions[$row->zone_id] + $row->impressions) : $row->impressions;
            $clicks[$row->zone_id] = isset($clicks[$row->zone_id]) ? ($clicks[$row->zone_id] + $row->clicks) : $row->clicks;
        }
        //Log::info(print_r($zones, true));
        $sql = "SELECT SUM(affiliate_stats.impressions) as impressions, 
SUM(affiliate_stats.clicks) as clicks, 
affiliate_stats.cpm, 
affiliate_stats.zone_id,
affiliate_stats.site_id,
zones.pub_id 
FROM affiliate_stats
JOIN zones on affiliate_stats.zone_id = zones.id
WHERE affiliate_stats.stat_date = '$mydate'
GROUP BY site_id, zone_id, pub_id, cpm;";
        $result = DB::select($sql);
        //Log::info("Result selected".count($result)." rows");
        foreach($result as $row){
            $earnings = $row->cpm * ($row->impressions / 1000);
            $zones[$row->zone_id] = isset($zones[$row->zone_id]) ? ($zones[$row->zone_id] + $earnings) : $earnings;
            $impressions[$row->zone_id] = isset($impressions[$row->zone_id]) ? ($impressions[$row->zone_id] + $row->impressions) : $row->impressions;
            $clicks[$row->zone_id] = isset($clicks[$row->zone_id]) ? ($clicks[$row->zone_id] + $row->clicks) : $row->clicks;
	}
	//Log::info(print_r($zones, true));
        foreach($zones as $zone => $earned){
            /* check for publisher booking and enter one if not there */
            $booking = DB::select("SELECT * FROM publisher_bookings WHERE zone_id = $zone AND booking_date = '$mydate'");
            if(empty($booking)){
                /* need to create a booking */
                $row = DB::select("SELECT * FROM zones WHERE id = ?", array($zone));
                $newbooking = array('site_id' => $row[0]->site_id, 'zone_id' => $row[0]->id, 'pub_id' => $row[0]->pub_id, 'booking_date' => $mydate, 'commission_tier' => $commission_tier, 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()'));
                $newid = DB::table('publisher_bookings')->insertGetId($newbooking);
                $booking = DB::select("SELECT * FROM publisher_bookings WHERE zone_id = $zone AND booking_date = '$mydate'");  
            }
            DB::update("UPDATE publisher_bookings SET revenue = $earned, impressions = ".$impressions[$zone].", clicks = ".$clicks[$zone].", updated_at = NOW() WHERE id = ".$booking[0]->id);
            Log::info("Zone $zone earned $".$earned." on ".$impressions[$zone]." impressions and ".$clicks[$zone]." clicks.");
        }
        Log:info("ProcessEarnings completed normally\n");
    }
}
