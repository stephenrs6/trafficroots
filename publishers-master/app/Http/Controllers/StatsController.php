<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use Auth;
use DB;
use App\Site;
use App\Campaign;
use App\Stat;
use App\Zone;
use App\Country;
use App\Browser;
use App\Platform;
use App\OperatingSystem;
use App\CityStat;
use App\BrowserStat;
use App\OsStat;
use App\PlatformStat;
use App\StateStat;
use App\CountryStat;
use Carbon\Carbon;
ini_set('memory_limit','4096M');
set_time_limit(0);

class StatsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getIndex()
    {
    }
    public function site(Request $request)
    {
        $site = new Site();
        $mysite = $site->where('id', $request->site)->first();
        $startDate = Carbon::now()->firstOfYear();
        $endDate = Carbon::now()->endOfMonth();
        $stats = $mysite->getStats()
                ->where('stat_date', '>=', $startDate)
                ->where('stat_date', '<=', $endDate)->get();
        return view('site-stats', ['site' => $mysite, 'site_name' => $mysite['site_name'], 'stats' => $stats]);
    }
    public function filtered(Request $request)
    {
        $dateRange = explode(' - ', $request->daterange);
        $startDate = Carbon::parse($dateRange[0]);
        $endDate = Carbon::parse($dateRange[1]);
        $sites = $request->has('sites') ? $request->get('sites') : 0;
        if($sites){
            $sitesql = ' AND site_id IN ('.implode(",",$sites).') ';
            $filter_sites = $sites;
        }else{
            $sitesql = '';
            $filter_sites = 0;
        }

        $user = Auth::getUser();
        /* overall */
        $sql = "SELECT platform_stats.stat_date,
        sum(platform_stats.impressions) AS impressions,
        sum(platform_stats.clicks) AS clicks 
        FROM platform_stats
        WHERE platform_stats.user_id = ?
        $sitesql
        AND platform_stats.stat_date BETWEEN ? AND ?
        GROUP BY platform_stats.stat_date
        ORDER BY platform_stats.stat_date;";
        $dates = DB::select($sql, array($user->id, $startDate, $endDate));
        
 
        /* platform stats */
        $sql = "SELECT
        platforms.platform,
        sum(platform_stats.impressions) AS impressions,
        sum(platform_stats.clicks) AS clicks 
        FROM platform_stats
        JOIN platforms ON platform_stats.platform = platforms.id
        WHERE platform_stats.user_id = ?
        $sitesql
        AND platform_stats.stat_date BETWEEN ? AND ?
        GROUP BY platforms.platform
        ORDER BY impressions DESC;";
        $platform_stats = DB::select($sql, array($user->id, $startDate, $endDate));

        /* os stats */
        $sql = "SELECT
        operating_systems.os,
        sum(os_stats.impressions) AS impressions,
        sum(os_stats.clicks) AS clicks 
        FROM os_stats
        JOIN operating_systems ON os_stats.os = operating_systems.id
        WHERE os_stats.user_id = ?
        $sitesql
        AND os_stats.stat_date BETWEEN ? AND ?
        GROUP BY operating_systems.os
        ORDER BY impressions DESC;";
        $os_stats = DB::select($sql, array($user->id, $startDate, $endDate));  
        
        /* browser stats */
        $sql = "SELECT
        browsers.browser,
        sum(browser_stats.impressions) AS impressions,
        sum(browser_stats.clicks) AS clicks 
        FROM browser_stats
        JOIN browsers ON browser_stats.browser = browsers.id
        WHERE browser_stats.user_id = ?
        $sitesql
        AND browser_stats.stat_date BETWEEN ? AND ?
        GROUP BY browsers.browser
        ORDER BY impressions DESC;";
        $browser_stats = DB::select($sql, array($user->id, $startDate, $endDate));    
        
        /* state stats */
        $sql = "SELECT
        states.state_name,
        countries.country_short,
        sum(state_stats.impressions) AS impressions,
        sum(state_stats.clicks) AS clicks 
        FROM state_stats
        JOIN states ON state_stats.state_code = states.id
        JOIN countries ON states.country_id = countries.id
        WHERE state_stats.user_id = ?
        $sitesql
        AND state_stats.stat_date BETWEEN ? AND ?
        GROUP BY states.state_name, countries.country_short
        ORDER BY impressions DESC;";
        $state_stats = DB::select($sql, array($user->id, $startDate, $endDate));


        /* country stats */
        $sql = "SELECT
        countries.country_name,
        sum(country_stats.impressions) AS impressions,
        sum(country_stats.clicks) AS clicks 
        FROM country_stats
        JOIN countries ON country_stats.country_code = countries.id
        WHERE country_stats.user_id = ?
        $sitesql
        AND country_stats.stat_date BETWEEN ? AND ?
        GROUP BY countries.country_name
        ORDER BY impressions DESC;";
        $country_stats = DB::select($sql, array($user->id, $startDate, $endDate));        
 
        return view('pub-stats', array('dates' => $dates, 'os_stats' => $os_stats, 'platform_stats' => $platform_stats, 'browser_stats' => $browser_stats, 'state_stats' => $state_stats, 'country_stats' => $country_stats, 'startDate' => $startDate, 'endDate' => $endDate, 'filter_sites' => $filter_sites));

    }
    public function pub(Request $request)
    {
        $user = Auth::getUser();
        $startDate = Carbon::now()->firstOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        /* overall */
        $sql = 'SELECT platform_stats.stat_date,
        sum(platform_stats.impressions) AS impressions,
        sum(platform_stats.clicks) AS clicks 
        FROM platform_stats
        WHERE platform_stats.user_id = ?
        AND platform_stats.stat_date BETWEEN ? AND ?
        GROUP BY platform_stats.stat_date
        ORDER BY platform_stats.stat_date;';
        $dates = DB::select($sql, array($user->id, $startDate, $endDate));
        
       
        /* platform stats */
        $sql = "SELECT
        platforms.platform,
        sum(platform_stats.impressions) AS impressions,
        sum(platform_stats.clicks) AS clicks 
        FROM platform_stats
        JOIN platforms ON platform_stats.platform = platforms.id
        WHERE platform_stats.user_id = ?
        AND platform_stats.stat_date BETWEEN ? AND ?
        GROUP BY platforms.platform
        ORDER BY impressions DESC;";
        $platform_stats = DB::select($sql, array($user->id, $startDate, $endDate));

        /* os stats */
        $sql = "SELECT
        operating_systems.os,
        sum(os_stats.impressions) AS impressions,
        sum(os_stats.clicks) AS clicks 
        FROM os_stats
        JOIN operating_systems ON os_stats.os = operating_systems.id
        WHERE os_stats.user_id = ?
        AND os_stats.stat_date BETWEEN ? AND ?
        GROUP BY operating_systems.os
        ORDER BY impressions DESC;";
        $os_stats = DB::select($sql, array($user->id, $startDate, $endDate));  
        
        /* browser stats */
        $sql = "SELECT
        browsers.browser,
        sum(browser_stats.impressions) AS impressions,
        sum(browser_stats.clicks) AS clicks 
        FROM browser_stats
        JOIN browsers ON browser_stats.browser = browsers.id
        WHERE browser_stats.user_id = ?
        AND browser_stats.stat_date BETWEEN ? AND ?
        GROUP BY browsers.browser
        ORDER BY impressions DESC;";
        $browser_stats = DB::select($sql, array($user->id, $startDate, $endDate));    
        
        /* state stats */
        $sql = "SELECT
        states.state_name,
        countries.country_short,
        sum(state_stats.impressions) AS impressions,
        sum(state_stats.clicks) AS clicks 
        FROM state_stats
        JOIN states ON state_stats.state_code = states.id
        JOIN countries ON states.country_id = countries.id
        WHERE state_stats.user_id = ?
        AND state_stats.stat_date BETWEEN ? AND ?
        GROUP BY states.state_name, countries.country_short
        ORDER BY impressions DESC;";
        $state_stats = DB::select($sql, array($user->id, $startDate, $endDate));

        /* country stats */
        $sql = "SELECT
        countries.country_name,
        sum(country_stats.impressions) AS impressions,
        sum(country_stats.clicks) AS clicks 
        FROM country_stats
        JOIN countries ON country_stats.country_code = countries.id
        WHERE country_stats.user_id = ?
        AND country_stats.stat_date BETWEEN ? AND ?
        GROUP BY countries.country_name
        ORDER BY impressions DESC;";
        $country_stats = DB::select($sql, array($user->id, $startDate, $endDate));        

        return view('pub-stats', array('dates' => $dates, 'filter_sites' => 0, 'os_stats' => $os_stats, 'platform_stats' => $platform_stats, 'browser_stats' => $browser_stats, 'state_stats' => $state_stats, 'country_stats' => $country_stats, 'startDate' => $startDate, 'endDate' => $endDate));
    }
    public function filteredCampaigns(Request $request)
    {
        $dateRange = explode(' - ', $request->daterange);
        $startDate = Carbon::parse($dateRange[0]);
        $endDate = Carbon::parse($dateRange[1]);
        $sites = $request->has('sites') ? $request->get('sites') : Auth::getUser()->sites->pluck('id');
        $stats = Stat::whereIn('site_id', $sites);
        
        if ($request->has('countries')) {
            $stats->whereIn('country_id', $request->get('countries'));
        }
        $stats = $stats
            ->where('stat_date', '>=', $startDate->toDateString())
            ->where('stat_date', '<=', $endDate->toDateString())
            ->get();

        return view('pub-stats', compact('stats', 'startDate', 'endDate'));
    }
    public function campaign($campaign)
    {
        // $this->authorize('view', $campaign);
        $startDate = Carbon::now()->firstOfMonth()->toDateString();
        $endDate = Carbon::now()->endOfMonth()->toDateString();
        $campaign = Campaign::with(['stats' => function ($query) use ($startDate, $endDate) {
            $query
                ->with(['city','country','state','platformType','operatingSystem','browserType'])
                ->where('stat_date', '>=', $startDate)
                ->where('stat_date', '<=', $endDate);
        },'category','type'])
            ->where('id', $campaign)
            ->first();
        return view('campaign-stats', compact('campaign', 'startDate', 'endDate'));
    }

    public function zone(Request $request, Zone $zone)
    {
	    // $this->authorize('view', $zone);
	if($request->has('daterange')){
            $stuff = explode(' - ', $request->get('daterange'));
	    $startDate = date('Y-m-d', strtotime($stuff[0]));
	    $endDate = date('Y-m-d', strtotime($stuff[1]));
	}else{
            $startDate = Carbon::now()->toDateString();
	    $endDate = Carbon::now()->toDateString();
	}
            $stats = $zone->stats
              ->where('stat_date', '>=', $startDate)
              ->where('stat_date', '<=', $endDate);
        
        return view('zone-stats', compact('zone', 'stats', 'startDate', 'endDate'));
    }
    /**
     * @author Cary White
     * @returns View
     * @access public
     * returns stats view by site and range
     */
    public function getSiteStats($site_id, $range)
    {
        try {
            $user = Auth::getUser();
            $site = Site::where('id', $site_id)->first();
            if (!$user->is_admin) {
                if (!$site->user_id == $user->id) {
                    return false;
                }
            }
            $zone_count = Zone::where('site_id', $site_id)->count();

            switch ($range) {
                case 1:
                    $start_date = date('Y-m-d', strtotime('-1 week'));
                    $range_desc = "Past Week";
                    break;
                case 2:
                    $start_date = date('Y-m-d', strtotime('-30 days'));
                    $range_desc = "Past 30 Days";
                    break;
                case 3:
                    $start_date = date('Y-m-d', strtotime('first day of this month'));
                    $range_desc = "Month to Date";
                    break;
                case 4:
                    $start_date = date('Y-m-d', strtotime('first day of this year'));
                    $range_desc = "Year to Date";
                    break;

            }
            $query = "SELECT * 
                     FROM stats
                     WHERE site_id = $site_id
                     AND `stat_date` BETWEEN '$start_date' AND '".date('Y-m-d')."'";
            $result = DB::select($query);
            $browsers = Browser::all();
            $platforms = Platform::all();
            $operating_systems = OperatingSystem::all();
            $sitedata = array();
            $zones = array();
            $big = array();
            $imps = 0;
            $clicks = 0;
            if (sizeof($result)) {
                foreach ($result as $row) {
                    if (isset($sitedata[$row->stat_date][$row->country_id]['impressions'])) {
                        $sitedata[$row->stat_date][$row->country_id]['impressions'] += $row->impressions;
                    } else {
                        $sitedata[$row->stat_date][$row->country_id]['impressions'] = $row->impressions;
                    }
                    if (isset($sitedata[$row->stat_date][$row->country_id]['clicks'])) {
                        $sitedata[$row->stat_date][$row->country_id]['clicks'] += $row->clicks;
                    } else {
                        $sitedata[$row->stat_date][$row->country_id]['clicks'] = $row->clicks;
                    }
                    $clicks += $row->clicks;
                    $imps += $row->impressions;
                    if (isset($big['browsers'][$row->browser])) {
                        $big['browsers'][$row->browser] += $row->impressions;
                    } else {
                        $big['browsers'][$row->browser] = $row->impressions;
                    }
                    if (isset($big['platforms'][$row->platform])) {
                        $big['platforms'][$row->platform] += $row->impressions;
                    } else {
                        $big['platforms'][$row->platform] = $row->impressions;
                    }
                    if (isset($big['os'][$row->os])) {
                        $big['os'][$row->os] += $row->impressions;
                    } else {
                        $big['os'][$row->os] = $row->impressions;
                    }
                }
            }
            return view('stats', ['site' => $site, 'big' => $big, 'range' => $range_desc, 'zone_count' => $zone_count, 'browsers' => $browsers, 'platforms' => $platforms, 'operating_systems' => $operating_systems, 'sitedata' => $sitedata, 'zones' => $zones, 'imps' => $imps, 'clicks' => $clicks, 'startDate' => $start_date, 'endDate' => date('Y-m-d')]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
    /**
     * @author Cary White
     * @returns View
     * @access public
     * return data by zone id
     */
    public function getZoneStats($zone_id, $range)
    {
        try {
            $start_date = $this->getRange($range);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
    public function campaignStats(Request $request)
    {
	    $user = Auth::getUser();
            $campaign = Campaign::where('id', $request->id)->where('user_id', $user->id)->first();
	    if($campaign){
		    $campaign_name = $campaign['campaign_name'];
            }else{
		    return redirect('/campaigns');
	    }
	if($request->has('daterange')){
            $stuff = explode(' - ', $request->get('daterange'));
	    $startDate = date('F j, Y', strtotime($stuff[0]));
	    $endDate = date('F j, Y', strtotime($stuff[1]));
	    $startQ = date('Y-m-d', strtotime($startDate));
	    $endQ = date('Y-m-d', strtotime($endDate));
	}else{
            $startDate = date('F j, Y');
	    $endDate = date('F j, Y');
	    $startQ = date('Y-m-d', strtotime($startDate));
	    $endQ = date('Y-m-d', strtotime($endDate));
	}
         /* today's traffic by site */
	    $site_traffic = array();
	    DB::statement("SET sql_mode = '';");
        $sql = 'select sum(stats.impressions) as impressions,
                 sum(stats.clicks) as clicks,
                 stats.site_id,
		 sites.site_name,
                 campaigns.campaign_name
                 from trafficroots.stats
                 join trafficroots.sites
                 on stats.site_id = sites.id
                 join trafficroots.bids
                 on stats.bid_id = bids.id
                 join trafficroots.campaigns
                 on bids.campaign_id = campaigns.id
                 where stats.stat_date between ? and ?
		 and campaigns.id = ?
                 and campaigns.user_id = ?
                 group by stats.site_id
                 order by impressions desc;';

        $traffic = DB::select($sql, array($startQ, $endQ, $request->id, $user->id));
        foreach($traffic as $row){
		$site_traffic[$row->site_id]['impressions'] = isset($site_traffic[$row->site_id]) ? $site_traffic[$row->site_id]['impressions'] + $row->impressions : $row->impressions;
                $site_traffic[$row->site_id]['clicks'] = isset($site_traffic[$row->site_id]['clicks']) ? $site_traffic[$row->site_id]['clicks'] + $row->clicks : $row->clicks;

		$site_traffic[$row->site_id]['site_name'] = $row->site_name;
		$campaign_name = $row->campaign_name;
	}
        $todays_traffic = 0;
        $todays_clicks = 0;
	foreach($site_traffic as $row){ 
		$todays_traffic += $row['impressions'];
		$todays_clicks += $row['clicks'];
	}
  

        $todays_ctr = $todays_traffic ? round($todays_clicks / $todays_traffic, 4) : 0.0000;

           $sql = 'select sum(stats.impressions) as impressions,
                  sum(stats.clicks) as clicks,
                  stats.country_id,
                  countries.country_short,
                  countries.country_name
                  from trafficroots.stats
                  join trafficroots.countries
                  on stats.country_id = countries.id
                  join trafficroots.bids
                  on stats.bid_id = bids.id
                  join trafficroots.campaigns
                  on bids.campaign_id = campaigns.id
                  where stats.stat_date between ? and ?
		  and campaigns.id = ?
                  and campaigns.user_id = ?
                  group by stats.country_id
                  order by impressions desc;';
         $geo_traffic = DB::select($sql, array($startQ, $endQ, $request->id, $user->id));

         $sql = 'select sum(stats.impressions) as impressions,
                 sum(stats.clicks) as clicks,
                 stats.state_code,
                 states.state_name,
                 states.state_short
                 from trafficroots.stats
                 join trafficroots.states
                 on stats.state_code = states.id
                  join trafficroots.bids
                  on stats.bid_id = bids.id
                  join trafficroots.campaigns
                  on bids.campaign_id = campaigns.id
                  where stats.stat_date between ? and ?
		  and campaigns.id = ?
                 and campaigns.user_id = ?
                 and stats.country_id = 840
                 group by stats.state_code
                 order by impressions desc
                 limit 20;';
          $state_traffic = DB::select($sql, array($startQ, $endQ, $request->id, $user->id));

          $sql = 'select sum(stats.impressions) as impressions,
                 sum(stats.clicks) as clicks,
                 stats.platform,
                 platforms.platform as description
                 from trafficroots.stats
                 join trafficroots.platforms
                 on stats.platform = platforms.id
                  join trafficroots.bids
                  on stats.bid_id = bids.id
                  join trafficroots.campaigns
                  on bids.campaign_id = campaigns.id
                  where stats.stat_date between ? and ?
		  and campaigns.id = ?
                 and campaigns.user_id = ?
                 group by stats.platform
                 order by impressions desc;';
          $platforms = DB::select($sql, array($startQ, $endQ, $request->id, $user->id));

          $sql = 'select sum(stats.impressions) as impressions,
                 sum(stats.clicks) as clicks,
                 stats.browser,
                 browsers.browser as description
                 from trafficroots.stats
                 join trafficroots.browsers
                 on stats.browser = browsers.id
                  join trafficroots.bids
                  on stats.bid_id = bids.id
                  join trafficroots.campaigns
                  on bids.campaign_id = campaigns.id
                  where stats.stat_date between ? and ?
		  and campaigns.id = ?
                  and campaigns.user_id = ?
                 group by stats.browser
                 order by impressions desc;';
          $browsers = DB::select($sql, array($startQ, $endQ, $request->id, $user->id));

          $sql = 'select sum(stats.impressions) as impressions,
                 sum(stats.clicks) as clicks,
                 stats.os,
                 operating_systems.os as description
                 from trafficroots.stats
                 join trafficroots.operating_systems
                 on stats.os = operating_systems.id
                  join trafficroots.bids
                  on stats.bid_id = bids.id
                  join trafficroots.campaigns
                  on bids.campaign_id = campaigns.id
                  where stats.stat_date between ? and ?
		  and campaigns.id = ?
                  and campaigns.user_id = ?
                 group by stats.os
                 order by impressions desc;';
          $operating_systems = DB::select($sql, array($startQ, $endQ, $request->id, $user->id));

	return view('campaign_stats', array('site_traffic' => $site_traffic, 
		                            'campaign_id' => $request->id, 
					    'todays_traffic' => $todays_traffic, 
					    'todays_clicks' => $todays_clicks, 
					    'todays_ctr' => $todays_ctr,
				            'campaign_name' => $campaign_name,
                                            'geo_traffic' => $geo_traffic,
                                            'state_traffic' => $state_traffic,
                                            'platforms' => $platforms,
                                            'browsers' => $browsers,
					    'operating_systems' => $operating_systems,
					    'startDate' => $startDate,
					    'endDate' => $endDate,
				            'datestring' => date('l jS \of F Y h:i:s A')));
    }
    public function zoneStats(Request $request)
    {
	    $user = Auth::getUser();
            $zone = Zone::where('id', $request->zone)->where('pub_id', $user->id)->first();
	    if($zone){
		    $zone_name = $zone['description'];
            }else{
		    return redirect('/sites');
	    }
	if($request->has('daterange')){
            $stuff = explode(' - ', $request->get('daterange'));
	    $startDate = date('F j, Y', strtotime($stuff[0]));
	    $endDate = date('F j, Y', strtotime($stuff[1]));
	    $startQ = date('Y-m-d', strtotime($startDate));
	    $endQ = date('Y-m-d', strtotime($endDate));
	}else{
            $startDate = date('F j, Y');
	    $endDate = date('F j, Y');
	    $startQ = date('Y-m-d', strtotime($startDate));
	    $endQ = date('Y-m-d', strtotime($endDate));
	}

	    $sql = 'select sum(zone_stats.impressions) as impressions,
		    sum(zone_stats.clicks) as clicks
                    from trafficroots.zone_stats
                    where zone_stats.zone_id = ?
                    and zone_stats.stat_date BETWEEN ? AND ?';
            $result = DB::select($sql, array($request->zone,$startQ, $endQ));
            $todays_traffic = intval($result[0]->impressions);
	    $todays_clicks = intval($result[0]->clicks);
            $todays_ctr = $todays_traffic ? round($todays_clicks / $todays_traffic, 4) : 0.0000;

	    $sql = 'select sum(stats.impressions) as impressions,
                  sum(stats.clicks) as clicks,
                  stats.country_id,
                  countries.country_short,
                  countries.country_name
                  from trafficroots.stats
                  join trafficroots.countries
                  on stats.country_id = countries.id
                  where stats.stat_date between ? and ?
                  and stats.zone_id = ?
                  group by stats.country_id
                  order by impressions desc;';
         $geo_traffic = DB::select($sql, array($startQ, $endQ, $request->zone));

         $sql = 'select sum(stats.impressions) as impressions,
                 sum(stats.clicks) as clicks,
                 stats.state_code,
                 states.state_name,
                 states.state_short
                 from trafficroots.stats
                 join trafficroots.states
                 on stats.state_code = states.id
                  where stats.stat_date between ? and ?
                 and stats.zone_id = ?
                 and stats.country_id = 840
                 group by stats.state_code
                 order by impressions desc
                 limit 20;';
          $state_traffic = DB::select($sql, array($startQ, $endQ, $request->zone));

          $sql = 'select sum(stats.impressions) as impressions,
                 sum(stats.clicks) as clicks,
                 stats.platform,
                 platforms.platform as description
                 from trafficroots.stats
                 join trafficroots.platforms
                 on stats.platform = platforms.id
                  where stats.stat_date between ? and ?
                 and stats.zone_id = ?
                 group by stats.platform
                 order by impressions desc;';
          $platforms = DB::select($sql, array($startQ, $endQ, $request->zone));

          $sql = 'select sum(stats.impressions) as impressions,
                 sum(stats.clicks) as clicks,
                 stats.browser,
                 browsers.browser as description
                 from trafficroots.stats
                 join trafficroots.browsers
                 on stats.browser = browsers.id
                  where stats.stat_date between ? and ?
                  and stats.zone_id = ?
                 group by stats.browser
                 order by impressions desc;';
          $browsers = DB::select($sql, array($startQ, $endQ, $request->zone));

          $sql = 'select sum(stats.impressions) as impressions,
                 sum(stats.clicks) as clicks,
                 stats.os,
                 operating_systems.os as description
                 from trafficroots.stats
                 join trafficroots.operating_systems
                 on stats.os = operating_systems.id
                  where stats.stat_date between ? and ?
                  and stats.zone_id = ?
                 group by stats.os
                 order by impressions desc;';
          $operating_systems = DB::select($sql, array($startQ, $endQ, $request->zone));

	return view('zone_stats', array( 
		                            'zone' => $zone_name, 
					    'todays_traffic' => $todays_traffic, 
					    'todays_clicks' => $todays_clicks, 
					    'todays_ctr' => $todays_ctr,
                                            'geo_traffic' => $geo_traffic,
                                            'state_traffic' => $state_traffic,
                                            'platforms' => $platforms,
                                            'browsers' => $browsers,
					    'operating_systems' => $operating_systems,
					    'startDate' => $startDate,
					    'endDate' => $endDate,
				            'datestring' => date('l jS \of F Y h:i:s A')));
    }

    public function bigData($date = '')
    {
        /* populate stats tables */
        if($date == '') $date = date('Y-m-d', strtotime("yesterday"));
        Log::info('Running Big Data for '.$date);
        CityStat::where('stat_date', $date)->delete();
        BrowserStat::where('stat_date', $date)->delete();
        PlatformStat::where('stat_date', $date)->delete();
        OsStat::where('stat_date', $date)->delete();
        CountryStat::where('stat_date', $date)->delete();
        StateStat::where('stat_date', $date)->delete();
        Log::info('Cleared stats tables for '.$date);
        $sql = "SELECT DISTINCT(zone_id), stats.site_id, zones.pub_id  
                FROM stats 
                JOIN zones ON stats.zone_id = zones.id
                WHERE stat_date = ?";
        $zones = DB::select($sql, array($date));
        Log::info(count($zones).' zones found');
        foreach($zones as $zone){
            Log::info('Running Zone '.$zone->zone_id);
            /* city stats */

            $insert = array();
            $sql = "SELECT SUM(impressions) AS impressions,
            SUM(clicks) AS clicks,
            zone_id,
            city_code AS id,
            state_code
            FROM stats
            WHERE zone_id = ?
            AND stat_date = ?
            GROUP BY zone_id, id, state_code
            ORDER BY impressions DESC;";
            $result = DB::select($sql, array($zone->zone_id, $date));
            $counter = 0;
            foreach($result as $row){
                $data = array('user_id' => $zone->pub_id, 
                              'zone_id' => $row->zone_id,
                              'site_id' => $zone->site_id,
                              'city_code' => $row->id,
                              'state_code' => $row->state_code,
                              'stat_date' => $date,
                              'impressions' => $row->impressions,
                              'clicks' => $row->clicks,
                              'created_at' => date('Y-m-d H:i:s'),
                              'updated_at' => date('Y-m-d H:i:s'));
                $insert[] = $data;
                $counter += 1;
                if($counter >= 1000){
                    CityStat::insert($insert);
                    $insert = array();
                    Log::info('Inserted '.$counter.' records');
                    $counter = 0;
                }
            }
            if($counter) {
                CityStat::insert($insert);
                Log::info('Inserted '.$counter.' records');  
            }
            $insert = array();
            $prefix = "INSERT INTO city_stats (user_id,zone_id,site_id,city_code,state_code,stat_date,impressions,clicks,created_at,updated_at) VALUES";
            $suffix = " ON DUPLICATE KEY UPDATE impressions = impressions + VALUES(impressions), clicks = clicks + VALUES(clicks), updated_at = NOW();";
            $sql = "SELECT SUM(impressions) AS impressions,
            SUM(clicks) AS clicks,
            zone_id,
            city_code AS id,
            state_code
            FROM affiliate_stats
            WHERE zone_id = ?
            AND stat_date = ?
            GROUP BY zone_id, id, state_code
            ORDER BY impressions DESC;";
            $result = DB::select($sql, array($zone->zone_id, $date));
            $counter = 0;
            foreach($result as $row){
                $data = "(".$zone->pub_id.",".$row->zone_id.",".$zone->site_id.",".$row->id.",".$row->state_code.",'".$date."',".$row->impressions.",".$row->clicks.",'".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."')";
                $insert[] = $data;
                $counter += 1;
                if($counter >= 1000){
                    $pairs = implode(',',$insert);
                    $sql = $prefix.$pairs.$suffix;
                    DB::insert($sql);
                    $insert = array();
                    Log::info('Inserted '.$counter.' records');
                    $counter = 0;
                }
            }
            if($counter) {
                $pairs = implode(',',$insert);
                $sql = $prefix.$pairs.$suffix;
                DB::insert($sql);
                Log::info('Inserted '.$counter.' records');  
            }            
            /* state stats */
            $insert = array();
            $sql = "SELECT SUM(impressions) AS impressions,
            SUM(clicks) AS clicks,
            zone_id,
            state_code
            FROM stats
            WHERE zone_id = ?
            AND stat_date = ?
            GROUP BY zone_id, state_code
            ORDER BY impressions DESC;";
            $result = DB::select($sql, array($zone->zone_id, $date));
            $counter = 0;
            foreach($result as $row){
                $data = array('user_id' => $zone->pub_id, 
                              'zone_id' => $row->zone_id,
                              'site_id' => $zone->site_id,
                              'state_code' => $row->state_code,
                              'stat_date' => $date,
                              'impressions' => $row->impressions,
                              'clicks' => $row->clicks,
                              'created_at' => date('Y-m-d H:i:s'),
                              'updated_at' => date('Y-m-d H:i:s'));
                $insert[] = $data;
                $counter += 1;
                if($counter >= 1000){
                    StateStat::insert($insert);
                    $insert = array();
                    Log::info('Inserted '.$counter.' records');
                    $counter = 0;
                }
            }
            if($counter) {
                StateStat::insert($insert);
                Log::info('Inserted '.$counter.' records');  
            }
            $insert = array();
            $prefix = "INSERT INTO state_stats (user_id,zone_id,site_id,state_code,stat_date,impressions,clicks,created_at,updated_at) VALUES";
            $suffix = " ON DUPLICATE KEY UPDATE impressions = impressions + VALUES(impressions), clicks = clicks + VALUES(clicks), updated_at = NOW();";
            $sql = "SELECT SUM(impressions) AS impressions,
            SUM(clicks) AS clicks,
            zone_id,
            state_code
            FROM affiliate_stats
            WHERE zone_id = ?
            AND stat_date = ?
            GROUP BY zone_id, state_code
            ORDER BY impressions DESC;";
            $result = DB::select($sql, array($zone->zone_id, $date));
            $counter = 0;
            foreach($result as $row){
                $data = "(".$zone->pub_id.",".$row->zone_id.",".$zone->site_id.",".$row->state_code.",'".$date."',".$row->impressions.",".$row->clicks.",'".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."')";
                $insert[] = $data;
                $counter += 1;
                if($counter >= 1000){
                    $sql = $prefix.implode(",",$insert).$suffix;
                    DB::insert($sql);
                    $insert = array();
                    Log::info('Inserted '.$counter.' records');
                    $counter = 0;
                }
            }
            if($counter) {
                $sql = $prefix.implode(",",$insert).$suffix;
                DB::insert($sql);
                Log::info('Inserted '.$counter.' records');  
            }                
            /* country stats */
            $insert = array();
            $sql = "SELECT SUM(impressions) AS impressions,
            SUM(clicks) AS clicks,
            zone_id,
            country_id
            FROM stats
            WHERE zone_id = ?
            AND stat_date = ?
            GROUP BY zone_id, country_id
            ORDER BY impressions DESC;";
            $result = DB::select($sql, array($zone->zone_id, $date));
            $counter = 0;
            foreach($result as $row){
                $data = array('user_id' => $zone->pub_id, 
                              'zone_id' => $row->zone_id,
                              'site_id' => $zone->site_id,
                              'country_code' => $row->country_id,
                              'stat_date' => $date,
                              'impressions' => $row->impressions,
                              'clicks' => $row->clicks,
                              'created_at' => date('Y-m-d H:i:s'),
                              'updated_at' => date('Y-m-d H:i:s'));
                $insert[] = $data;
                $counter += 1;
                if($counter >= 1000){
                    CountryStat::insert($insert);
                    $insert = array();
                    Log::info('Inserted '.$counter.' records');
                    $counter = 0;
                }
            }
            if($counter) {
                CountryStat::insert($insert);
                Log::info('Inserted '.$counter.' records');  
            }
            $insert = array();
            $prefix = "INSERT INTO country_stats (user_id,zone_id,site_id,country_code,stat_date,impressions,clicks,created_at,updated_at) VALUES";
            $suffix = " ON DUPLICATE KEY UPDATE impressions = impressions + VALUES(impressions), clicks = clicks + VALUES(clicks), updated_at = NOW();";
            $sql = "SELECT SUM(impressions) AS impressions,
            SUM(clicks) AS clicks,
            country_id
            FROM affiliate_stats
            WHERE zone_id = ?
            AND stat_date = ?
            GROUP BY zone_id, country_id
            ORDER BY impressions DESC;";
            $result = DB::select($sql, array($zone->zone_id, $date));
            $counter = 0;
            foreach($result as $row){
                $data = "(".$zone->pub_id.",".$zone->zone_id.",".$zone->site_id.",".$row->country_id.",'".$date."',".$row->impressions.",".$row->clicks.",'".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."')";
                $insert[] = $data;
                $counter += 1;
                if($counter >= 1000){
                    $sql = $prefix.implode(",",$insert).$suffix;
                    DB::insert($sql);
                    $insert = array();
                    Log::info('Inserted '.$counter.' records');
                    $counter = 0;
                }
            }
            if($counter) {
                $sql = $prefix.implode(",",$insert).$suffix;
                DB::insert($sql);
                Log::info('Inserted '.$counter.' records');  
            }  

            /* browser stats */

            $insert = array();
            $sql = "SELECT SUM(impressions) AS impressions,
            SUM(clicks) AS clicks,
            zone_id, 
            browser
            FROM stats
            WHERE zone_id = ?
            AND stat_date = ?
            GROUP BY zone_id, browser
            ORDER BY impressions DESC;";
            $result = DB::select($sql, array($zone->zone_id, $date));
            $counter = 0;
            foreach($result as $row){
                $data = array('user_id' => $zone->pub_id, 
                              'site_id' => $zone->site_id,
                              'zone_id' => $row->zone_id,
                              'browser' => $row->browser,
                              'stat_date' => $date,
                              'impressions' => $row->impressions,
                              'clicks' => $row->clicks,
                              'created_at' => date('Y-m-d H:i:s'),
                              'updated_at' => date('Y-m-d H:i:s'));
                $insert[] = $data;
                $counter += 1;
                if($counter >= 1000){
                    BrowserStat::insert($insert);
                    $insert = array();
                    Log::info('Inserted '.$counter.' records');
                    $counter = 0;
                }
            }
            if($counter) {
                BrowserStat::insert($insert);
                Log::info('Inserted '.$counter.' records');
            }
            $pairs = array();
            $sql = "SELECT SUM(impressions) AS impressions,
            SUM(clicks) AS clicks,
            zone_id, 
            browser
            FROM affiliate_stats
            WHERE zone_id = ?
            AND stat_date = ?
            GROUP BY zone_id, browser
            ORDER BY impressions DESC;";
            $result = DB::select($sql, array($zone->zone_id, $date));
            $counter = 0;
            $prefix = "INSERT INTO browser_stats (`user_id`,`zone_id`,`site_id`,`browser`,`stat_date`,`impressions`,`clicks`,`created_at`,`updated_at`) VALUES";
            $suffix = " ON DUPLICATE KEY UPDATE impressions = impressions + VALUES(`impressions`), clicks = clicks + VALUES(`clicks`), updated_at = CURDATE();";
            foreach($result as $row){
                $set = "(".$zone->pub_id.",".$row->zone_id.",".$zone->site_id.",".$row->browser.",'".$date."',".$row->impressions.",".$row->clicks.",'".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."')";
                $pairs[] = $set;
                $counter += 1;
                if($counter >= 1000){
                    $sql = $prefix . implode(",",$pairs) . $suffix;
                    DB::insert($sql);
                    $pairs = array();
                    $counter = 0;
                }                   
            }
            if($counter){
                $sql = $prefix . implode(",",$pairs) . $suffix;
                DB::insert($sql);
                $pairs = array();
                $counter = 0;
            } 
            /* platform stats */
            
            $insert = array();
            $sql = "SELECT SUM(impressions) AS impressions,
            SUM(clicks) AS clicks,
            zone_id, 
            platform
            FROM stats
            WHERE zone_id = ?
            AND stat_date = ?
            GROUP BY zone_id, platform
            ORDER BY impressions DESC;";
            $result = DB::select($sql, array($zone->zone_id, $date));
            $counter = 0;
            foreach($result as $row){
                $data = array('user_id' => $zone->pub_id, 
                              'site_id' => $zone->site_id,
                              'zone_id' => $row->zone_id,
                              'platform' => $row->platform,
                              'stat_date' => $date,
                              'impressions' => $row->impressions,
                              'clicks' => $row->clicks,
                              'created_at' => date('Y-m-d H:i:s'),
                              'updated_at' => date('Y-m-d H:i:s'));
                $insert[] = $data;
                $counter += 1;
                if($counter >= 1000){
                    PlatformStat::insert($insert);
                    $insert = array();
                    Log::info('Inserted '.$counter.' records');
                    $counter = 0;
                }
            }
            if($counter) {
                PlatformStat::insert($insert);
                Log::info('Inserted '.$counter.' records');
            }
            $pairs = array();
            $sql = "SELECT SUM(impressions) AS impressions,
            SUM(clicks) AS clicks,
            zone_id, 
            platform
            FROM affiliate_stats
            WHERE zone_id = ?
            AND stat_date = ?
            GROUP BY zone_id, platform
            ORDER BY impressions DESC;";
            $result = DB::select($sql, array($zone->zone_id, $date));
            $counter = 0;
            $prefix = "INSERT INTO platform_stats (`user_id`,`zone_id`,`site_id`,`platform`,`stat_date`,`impressions`,`clicks`,`created_at`,`updated_at`) VALUES";
            $suffix = " ON DUPLICATE KEY UPDATE impressions = impressions + VALUES(`impressions`), clicks = clicks + VALUES(`clicks`), updated_at = CURDATE();";
            foreach($result as $row){
                $set = "(".$zone->pub_id.",".$row->zone_id.",".$zone->site_id.",".$row->platform.",'".$date."',".$row->impressions.",".$row->clicks.",'".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."')";
                $pairs[] = $set;
                $counter += 1;
                if($counter >= 1000){
                    $sql = $prefix . implode(",",$pairs) . $suffix;
                    DB::insert($sql);
                    $pairs = array();
                    $counter = 0;
                }                   
            }
            if($counter){
                $sql = $prefix . implode(",",$pairs) . $suffix;
                DB::insert($sql);
                $pairs = array();
                $counter = 0;
            }         

            /* os stats */
            
            $insert = array();
            $sql = "SELECT SUM(impressions) AS impressions,
            SUM(clicks) AS clicks,
            zone_id,
            os
            FROM stats
            WHERE zone_id = ?
            AND stat_date = ?
            GROUP BY zone_id, os
            ORDER BY impressions DESC;";
            $result = DB::select($sql, array($zone->zone_id, $date));
            $counter = 0;
            foreach($result as $row){
                $data = array('user_id' => $zone->pub_id, 
                              'site_id' => $zone->site_id,
                              'zone_id' => $row->zone_id,
                              'os' => $row->os,
                              'stat_date' => $date,
                              'impressions' => $row->impressions,
                              'clicks' => $row->clicks,
                              'created_at' => date('Y-m-d H:i:s'),
                              'updated_at' => date('Y-m-d H:i:s'));
                $insert[] = $data;
                $counter += 1;
                if($counter >= 1000){
                    OsStat::insert($insert);
                    $insert = array();
                    Log::info('Inserted '.$counter.' records');
                    $counter = 0;
                }
            }
            if($counter) {
                OsStat::insert($insert);
                Log::info('Inserted '.$counter.' records');
            }
        
        $pairs = array();
        $sql = "SELECT SUM(impressions) AS impressions,
        SUM(clicks) AS clicks,
        zone_id,
        os
        FROM affiliate_stats
        WHERE zone_id = ?
        AND stat_date = ?
        GROUP BY zone_id, os
        ORDER BY impressions DESC;";
        $result = DB::select($sql, array($zone->zone_id, $date));
        $counter = 0;
        $prefix = "INSERT INTO os_stats (`user_id`,`zone_id`,`site_id`,`os`,`stat_date`,`impressions`,`clicks`,`created_at`,`updated_at`) VALUES";
        $suffix = " ON DUPLICATE KEY UPDATE impressions = impressions + VALUES(`impressions`), clicks = clicks + VALUES(`clicks`), updated_at = CURDATE();";
        foreach($result as $row){
            $set = "(".$zone->pub_id.",".$row->zone_id.",".$zone->site_id.",".$row->os.",'".$date."',".$row->impressions.",".$row->clicks.",'".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."')";
            $pairs[] = $set;
            $counter += 1;
            if($counter >= 1000){
                $sql = $prefix . implode(",",$pairs) . $suffix;
                DB::insert($sql);
                $pairs = array();
                $counter = 0;
            }                   
        }
        if($counter){
            $sql = $prefix . implode(",",$pairs) . $suffix;
            DB::insert($sql);
            $pairs = array();
            $counter = 0;
        }  
        
        }
        Log::info('Big Data Completed!');
    }

    public function reloadBigData()
    {
        $date = strtotime('2018-01-01');
        $now = time();
        do{
            $mydate = date('Y-m-d', $date);
            $this->bigData($mydate);
            $date += 86400;
        }while($date < $now);
    }
}
