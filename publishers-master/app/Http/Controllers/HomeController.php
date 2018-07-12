<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Site;
use App\Bank;
use App\User;
use App\Faq;
use App\LocationType;
use App\Category;
use App\StatusType;
use App\Folders;
use App\Country;
use App\Transaction;
use App\Payment;
use App\Campaign;
use App\Zone;
use App\PayoutSettings;
use App\PaymentMethod;
use App\TaxStatus;
use App\MinimumPayout;
use App\AffiliateStat;
use DB;
use Log;
use Session;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Mail;
use Illuminate\Hashing\BcryptHasher;
use App\Mail\ConfirmUser;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        setlocale(LC_MONETARY, 'en_US.utf8');
    }

    /* send confirmation email with redis token */
    public function sendConfirmation(Request $request){
        $user = Auth::getUser();
	if($user->status){
		Log::info($user->name.' hit the confirmation email route, but is already activated');
		Log::info('' . bin2hex(random_bytes(8)));
		return redirect('/home');
	}else{
            Log::info('Sending confirmation email to '.$user->name);
	    $handle = bin2hex(random_bytes(8));
	    session(['sent_confirmation' => 1]);
            Redis::setex($handle, 86400 * 2, $user->id);
	    Log::info($handle);
	    Mail::to($user->email)->send(new ConfirmUser($user, $handle));
	    Log::info('Mail Sent!');
	    $request->session()->flash('status', 'Sending of Confirmation Email was successful!');
            return redirect('/home');
	}	
    }
    public function pwChange(Request $request)
    {
	    $user = Auth::getUser();
	    $hasher = new BcryptHasher();
	    Log::info($user->name.' is trying to change their password from '.$request->ip());
	    if(Auth::attempt(array('email' => $user->email, 'password' => $request->mypassword)))
	    {
		    Log::info($user->name.' is authenticated.');
		    if($request->newpass == $request->confirm)
		    {
			    User::where('id', $user->id)->update(array('password' => $hasher->make($request->newpass)));
			    Log::info($user->name.' successfully changed their password from '.$request->ip());
                            $request->session()->flash('status', 'Success! Password was updated!');
                            $request->session()->flash('status_type', 'success');
		    }else{
			    $request->session()->flash('status', 'Sorry! Your password and confirmation did not match.');
			    $request->session()->flash('status_type', 'error');
		    }
 	    }else{
		    $request->session()->flash('status', 'Sorry! That is not your current password. Please try again, or logout and use the forgot password tool.');
		    $request->session()->flash('status_type', 'error');
		    Log::info(bcrypt($request->mypassword) .' != '. $user->password);
                    Log::info($user->name.' FAILED to change their password from '.$request->ip());
	    }

	    return redirect('/profile');
    }
    public function advertiser()
    {
        return view('advertiser.dashboard', array('title' => 'Advertisers'));
    }
    public function advertiserFaq()
    {
        $faqs = Faq::where('faq_type', 1)->get();
        return view('faq_advertiser', array('faqs' => $faqs, 'title' => 'Advertiser FAQ'));
    }
    public function publisherFaq()
    {
	$faqs = Faq::where('faq_type', 2)->get();
        return view('faq_publisher', array('faqs' => $faqs, 'title' => 'Publisher FAQ'));
    }
    public function whoAmI()
    {
        $categories = Category::all();
        return view('whoami',array('categories' => $categories, 'title' => 'Info'));
    }
    public function pubType()
    {
        $user = Auth::getUser();
        User::where('id', $user->id)->update(array('user_type' => 1));
        return redirect('sites');
    }
    public function buyerType()
    {
        $user = Auth::getUser();
        User::where('id', $user->id)->update(array('user_type' => 2));
        return redirect('library');
    }
    /**
     * Show the advertiser`s library.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLibrary(Request $request)
    {
       if($request->has('daterange')){
        $dateRange = explode(' - ', $request->daterange);
	$startDate = Carbon::parse($dateRange[0]);
	$endDate = Carbon::parse($dateRange[1]);
       }else{
        $startDate = Carbon::now()->firstOfMonth()->toDateString();
	$endDate = Carbon::now()->endOfMonth()->toDateString();
       }
       $status_types = array();
       $status = StatusType::all();
       $status_types[] = 'Pending';
       foreach($status as $s){
           $status_types[$s->id] = $s->description;
       }
       $campaign_types = array();
       $campaign_types[1] = 'CPM';
       $campaign_types[2] = 'CPC';
       $location_types = LocationType::all();
       $categories = Category::all();
       $location = array();
       $width = array();
       $height = array();
       foreach($location_types as $type){
           $location[$type['id']] = $type['description'] . ' - ' . $type['width'] .'x'. $type['height'];
           $width[$type['id']] = $type['width'] + 50;
           $height[$type['id']] = $type['height'] + 50;
       }
        $category = array();
        foreach($categories as $cat){
           $category[$cat['id']] = $cat['category'];
        }
	$user = Auth::user();
        $media = DB::select('select * from media where user_id = '.$user->id);
	$links = DB::select('select * from links where user_id = '.$user->id);
        if($user->allow_folders){
            $allow_folders = true;
            $folders = DB::select('select * from folders where user_id = '.$user->id);
        }else{
            $allow_folders = false;
            $folders = array();
        }
	return view('library', array('user' => $user,
		                     'location_types' => $location, 
                                     'categories' => $category, 
                                     'campaign_types' => $campaign_types, 
                                     'width' => $width, 
                                     'height' => $height, 
                                     'status_types' => $status_types, 
                                     'media' => $media, 
                                     'links' => $links, 
				     'allow_folders' => $allow_folders,
				     'startDate' => $startDate,
				     'endDate' => $endDate, 
                                     'folders' => $folders));
    }    
    /**
     * Show the advertiser`s dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function buyers(Request $request)
    {
       $tab = $request->tab;
       $status_types = array();
       $status = StatusType::all();
       $status_types[] = 'Pending';
       foreach($status as $s){
           $status_types[$s->id] = $s->description;
       }
       $campaign_types = array();
       $campaign_types[1] = 'CPM';
       $campaign_types[2] = 'CPC';
       $location_types = LocationType::all();
       $categories = Category::all();
       $location = array();
       $width = array();
       $height = array();
       foreach($location_types as $type){
           $location[$type['id']] = $type['description'] . ' - ' . $type['width'] .'x'. $type['height'];
           $width[$type['id']] = $type['width'] + 50;
           $height[$type['id']] = $type['height'] + 50;
       }
        $category = array();
        foreach($categories as $cat){
           $category[$cat['id']] = $cat['category'];
        }
        $user = Auth::user();
        if($tab == 'campaigns'){
            $res = DB::select('select * from campaigns where user_id = '.$user->id);
            return view('campaigns', array('user' => $user, 'campaigns' => $res, 'location_types' => $location, 'categories' => $category, 'campaign_types' => $campaign_types, 'width' => $width, 'height' => $height, 'status_types' => $status_types));
        }
        if($tab == 'media'){
            $media = DB::select('select * from media where user_id = '.$user->id);
            return view('media', array('user' => $user, 'media' => $media, 'location_types' => $location, 'categories' => $category, 'campaign_types' => $campaign_types, 'width' => $width, 'height' => $height, 'status_types' => $status_types));
        }
        if($tab == 'folders'){
            $folders = DB::select('select * from folders where user_id = '.$user->id);
            return view('folders', array('user' => $user, 'folders' => $folders, 'location_types' => $location, 'categories' => $category, 'campaign_types' => $campaign_types, 'width' => $width, 'height' => $height, 'status_types' => $status_types));
        }
        if($tab == 'links'){
            $links = DB::select('select * from links where user_id = '.$user->id);
            return view('links', array('user' => $user, 'links' => $links, 'location_types' => $location, 'categories' => $category, 'campaign_types' => $campaign_types, 'width' => $width, 'height' => $height, 'status_types' => $status_types));
        }
        if($tab == 'account'){
            $bank = DB::select('SELECT * FROM bank WHERE user_id = '.$user->id.' ORDER BY id DESC LIMIT 1;');
            if(!sizeof($bank)){
                $data = array();
                $data['user_id'] = $user->id;
                $data['transaction_amount'] = 0.00;
                $data['running_balance'] = 0.00;
                $newbank = new Bank();
                $newbank->fill($data);
                $newbank->save();
                $bank = DB::select('SELECT * FROM bank WHERE user_id = '.$user->id.' ORDER BY id DESC LIMIT 1;');
            }            
            return view('account_buyer', array('user' => $user, 'bank' => $bank, 'location_types' => $location, 'categories' => $category, 'campaign_types' => $campaign_types, 'width' => $width, 'height' => $height, 'status_types' => $status_types));
        }

    }
    public function getPubInfo($id) {
        DB::statement("SET sql_mode = '';");
        $data = array();
        $sql = "SELECT 
SUM(publisher_bookings.revenue) * commission_tiers.publisher_factor AS earned,
SUM(publisher_bookings.impressions) as impressions,
SUM(publisher_bookings.clicks) as clicks,
commission_tiers.publisher_factor
FROM publisher_bookings
JOIN commission_tiers
ON publisher_bookings.commission_tier = commission_tiers.id
WHERE publisher_bookings.booking_date = CURDATE()
AND publisher_bookings.pub_id = $id
GROUP BY commission_tiers.publisher_factor;";
        $result = DB::select($sql);
        $data['earned_today'] = sizeof($result) ? $result[0]->earned : 0.00;
        $data['impressions_today'] = sizeof($result) ? $result[0]->impressions : 0;
        $data['clicks_today'] = sizeof($result) ? $result[0]->clicks : 0;
        $data['cpm_today'] = $data['impressions_today'] ? ($data['earned_today'] / ($data['impressions_today'] / 1000)) : 0.00;        

        $sql = "SELECT 
SUM(publisher_bookings.revenue) * commission_tiers.publisher_factor AS earned,
SUM(publisher_bookings.impressions) as impressions,
SUM(publisher_bookings.clicks) as clicks,
commission_tiers.publisher_factor
FROM publisher_bookings
JOIN commission_tiers
ON publisher_bookings.commission_tier = commission_tiers.id
WHERE publisher_bookings.booking_date = DATE_SUB(CURDATE(), INTERVAL 1 DAY)
AND publisher_bookings.pub_id = $id
GROUP BY commission_tiers.publisher_factor;";
        $result = DB::select($sql);
        $data['earned_yesterday'] = sizeof($result) ? $result[0]->earned : 0.00;
        $data['impressions_yesterday'] = sizeof($result) ? $result[0]->impressions : 0;
        $data['clicks_yesterday'] = sizeof($result) ? $result[0]->clicks : 0;
        $data['cpm_yesterday'] = $data['impressions_yesterday'] ? ($data['earned_yesterday'] / ($data['impressions_yesterday'] / 1000)) : 0.00;        


        $sql = "SELECT 
SUM(publisher_bookings.revenue) * commission_tiers.publisher_factor AS earned,
SUM(publisher_bookings.impressions) as impressions,
SUM(publisher_bookings.clicks) as clicks,
commission_tiers.publisher_factor
FROM publisher_bookings
JOIN commission_tiers
ON publisher_bookings.commission_tier = commission_tiers.id
WHERE publisher_bookings.booking_date >= '".date('Y-m-d', strtotime('first day of this month'))."'
AND publisher_bookings.pub_id = $id
GROUP BY commission_tiers.publisher_factor;";
        $data['earned_this_month'] = 0.00;
        $data['impressions_this_month'] = 0;
        $data['clicks_this_month'] = 0;
        foreach(DB::select($sql) as $row){
            if(!is_null($row->earned)) $data['earned_this_month'] += $row->earned;
            $data['impressions_this_month'] += is_null($row->impressions) ? 0 : $row->impressions;
            $data['clicks_this_month'] += is_null($row->clicks) ? 0 : $row->clicks;
        } 
        $data['cpm_this_month'] = $data['impressions_this_month'] ? ($data['earned_this_month'] / ($data['impressions_this_month'] / 1000)) : 0.00;
$sql = "SELECT 
SUM(publisher_bookings.revenue) * commission_tiers.publisher_factor AS earned,
SUM(publisher_bookings.impressions) as impressions,
SUM(publisher_bookings.clicks) as clicks,
commission_tiers.publisher_factor
FROM publisher_bookings
JOIN commission_tiers
ON publisher_bookings.commission_tier = commission_tiers.id
WHERE publisher_bookings.booking_date BETWEEN '".date('Y-m-d', strtotime('first day of last month'))."'
AND '".date('Y-m-d', strtotime('last day of last month'))."'
AND publisher_bookings.pub_id = $id
GROUP BY commission_tiers.publisher_factor;";
        $data['earned_last_month'] = 0.00;
        $data['impressions_last_month'] = 0;
        $data['clicks_last_month'] = 0;
        foreach(DB::select($sql) as $row){
            if(!is_null($row->earned)) $data['earned_last_month'] += $row->earned;
            $data['impressions_last_month'] += is_null($row->impressions) ? 0 : $row->impressions;
            $data['clicks_last_month'] += is_null($row->clicks) ? 0 : $row->clicks;            
        }
        $data['cpm_last_month'] = $data['impressions_last_month'] ? ($data['earned_last_month'] / ($data['impressions_last_month'] / 1000)) : 0.00;
$sql2 = "SELECT 
SUM(publisher_bookings.revenue) * commission_tiers.publisher_factor AS earned,
SUM(publisher_bookings.impressions) as impressions,
SUM(publisher_bookings.clicks) as clicks,
commission_tiers.publisher_factor
FROM publisher_bookings
JOIN commission_tiers
ON publisher_bookings.commission_tier = commission_tiers.id
WHERE publisher_bookings.booking_date >= '".date('Y')."-01-01'
AND publisher_bookings.pub_id = $id
GROUP BY commission_tiers.publisher_factor;";
        $data['earned_this_year'] = 0.00;
        $data['impressions_this_year'] = 0;
        $data['clicks_this_year'] = 0;
        foreach(DB::select($sql2) as $row2){
            if(!is_null($row2->earned)) $data['earned_this_year'] += $row2->earned;
            $data['impressions_this_year'] += is_null($row2->impressions) ? 0 : $row2->impressions;
            $data['clicks_this_year'] += is_null($row2->clicks) ? 0 : $row2->clicks;            
        }
        $data['cpm_this_year'] = $data['impressions_this_year'] ? ($data['earned_this_year'] / ($data['impressions_this_year'] / 1000)) : 0.00;

/* get number of days to go back for all traffic ever */
        $sql = 'SELECT
                DATEDIFF(MAX(booking_date), MIN(booking_date)) AS totaldiff
                FROM publisher_bookings
                WHERE pub_id = ?';
        foreach(DB::select($sql, array($id)) as $row){
            $goback = $row->totaldiff;
        }
        $data['last_thirty_days'] = array();        
for($i = $goback; $i > 0; $i--){
    $mydate = date('Y-m-d', strtotime("-$i days"));

$sql = "SELECT 
SUM(publisher_bookings.revenue) * commission_tiers.publisher_factor AS earned,
SUM(publisher_bookings.impressions) as impressions,
SUM(publisher_bookings.clicks) as clicks,
commission_tiers.publisher_factor
FROM publisher_bookings
JOIN commission_tiers
ON publisher_bookings.commission_tier = commission_tiers.id
WHERE publisher_bookings.booking_date = '$mydate'
AND publisher_bookings.pub_id = $id
GROUP BY commission_tiers.publisher_factor, publisher_bookings.booking_date
ORDER BY publisher_bookings.booking_date;";
        //Log::info($sql);
        $data['last_thirty_days'][date('m/d/Y',strtotime($mydate))] = array('timestamp' => strtotime($mydate) * 1000, 'impressions' => 0, 'clicks' => 0, 'earnings' => 0);
        foreach(DB::select($sql) as $row){
            $earnings = $row->earned;
            $impressions = $row->impressions;
            $clicks = $row->clicks;
            $data['last_thirty_days'][date('m/d/Y',strtotime($mydate))] = array('timestamp' => strtotime($mydate) * 1000, 'impressions' => $impressions, 'clicks' => $clicks, 'earnings' => $earnings);
        } 
}
        $data['active_zones'] = array();

$sql = "SELECT
	SUM(publisher_bookings.revenue) * commission_tiers.publisher_factor AS earned,
	SUM(publisher_bookings.impressions) as impressions,
	SUM(publisher_bookings.clicks) as clicks,
	sites.site_name,
        zones.handle,
	zones.description
	FROM publisher_bookings
	JOIN commission_tiers
	ON publisher_bookings.commission_tier = commission_tiers.id
	JOIN sites
	ON publisher_bookings.site_id = sites.id
	JOIN zones
	ON publisher_bookings.zone_id = zones.id
	WHERE publisher_bookings.booking_date = CURDATE()
	AND publisher_bookings.pub_id =	$id
        GROUP BY publisher_bookings.zone_id
        ORDER BY impressions DESC, clicks DESC;";
        foreach(DB::select($sql) as $row){
            $data['active_zones'][] = $row;
        }
        return $data;
    }

    /**
     * Show the publisher`s dashboard.
     * @author Cary White
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       $input = $request->all();
       $user = Auth::user();
       if(!$user->user_type){
           return view('whoami');
       }
        $sql = 'SELECT sites.*, site_themes.theme
                FROM sites 
                JOIN site_themes 
                ON sites.site_theme = site_themes.id
                WHERE sites.user_id = '.$user->id;
		
		
		if($request->has('daterange')){
			$dateRange = explode(' - ', $request->daterange);
	        $startDate = Carbon::parse($dateRange[0]);
			$endDate = Carbon::parse($dateRange[1]);
		}else{
			$startDate = Carbon::now()->toDateString();
			$endDate = Carbon::now()->toDateString();
		}
		
        $sites = DB::select($sql);
        $view_type = isset($input['type']) ? intval($input['type']) : 0;
        if(!$view_type) $view_type = $user->user_type;
        if($view_type == 1 || $view_type == 3){ 
            $pub_data = $this->getPubInfo($user->id);
            return view('home', ['user' => $user, 'title' => 'Publishers',
                            'sites' => $sites, 'pub_data' => $pub_data, 'view_type' => $view_type]);
        }
        if($view_type == 2){
            $buyer_data = $this->getBuyerInfo($user);
            return view('home', ['user' => $user, 'title' => 'Advertisers', 'buyer_data' => $buyer_data, 'view_type' => $view_type]);
        }
    }

    /**
     * Get buyer data
     * @author Cary White
     * @return array
     * @access public
     */
    public function getBuyerInfo($user)
    {
        DB::statement("SET sql_mode = '';");
        $data = array();
        /* balance */ 
        $sql = "SELECT running_balance AS balance FROM bank WHERE user_id = ? ORDER BY id DESC LIMIT 1";
	$data['current_balance'] = sizeof($bank = DB::select($sql, array($user->id))) ? $bank[0]->balance : 0.00;
        $t1 = microtime(true);	
        /* today */
        $sql = "SELECT SUM(campaign_stats.impressions) AS impressions, SUM(campaign_stats.clicks) AS clicks, campaign_stats.stat_date
                FROM campaign_stats
                WHERE campaign_stats.user_id = ?
                AND campaign_stats.stat_date = CURDATE();";
        $result = DB::select($sql, array($user->id));
        $t2 = microtime(true);
	Log::info('Got Today in '.round($t2 - $t1, 4).' seconds');
        $data['impressions_today'] = sizeof($result) ? $result[0]->impressions : 0;
        $data['clicks_today'] = sizeof($result) ? $result[0]->clicks : 0;
	$data['ctr_today'] = (float) $data['clicks_today'] ? round($data['clicks_today'] / $data['impressions_today'], 4) : 0.0000;
	$t1 = microtime(true);
        $sql = "SELECT spent FROM spend WHERE user_id = ? AND spend_date >= CURDATE() AND spent < 0";
	$result = DB::select($sql, array($user->id));
        $t2 = microtime(true);
	Log::info('Got Spend in '.round($t2 - $t1, 4).' seconds');
        $data['spent_today'] = sizeof($result) ? $result[0]->spent * -1 : 0;

        $data['cpm_today'] = (float) $data['impressions_today'] ? round($data['spent_today'] / ($data['impressions_today'] / 1000), 4) : 0.00;
        $data['cpc_today'] = (float) $data['clicks_today'] ? round($data['spent_today'] / $data['clicks_today'], 4) : 0.00;
        $t1 = microtime(true);
        $sql = "SELECT *
                FROM campaign_stats
                WHERE campaign_stats.user_id = ?
                AND campaign_stats.stat_date = CURDATE();";
         $result = DB::select($sql, array($user->id));
        $t2 = microtime(true);
        Log::info('Got Active Campaigns in '.round($t2 - $t1, 4).' seconds');
        $data['active_campaigns_yesterday'] = sizeof($result);

        $t1 = microtime(true);
        /* yesterday */
        $sql = "SELECT SUM(campaign_stats.impressions) AS impressions, SUM(campaign_stats.clicks) AS clicks, campaign_stats.stat_date
                FROM campaign_stats
                WHERE campaign_stats.user_id = ?
                AND campaign_stats.stat_date = DATE_SUB(CURDATE(), INTERVAL 1 DAY);";
        $result = DB::select($sql, array($user->id));
        $t2 = microtime(true);
        Log::info('Got Yesterday in '.round($t2 - $t1, 4).' seconds');
        $data['impressions_yesterday'] = sizeof($result) ? $result[0]->impressions : 0;
        $data['clicks_yesterday'] = sizeof($result) ? $result[0]->clicks : 0;
	$data['ctr_yesterday'] = (float) $data['clicks_yesterday'] ? round($data['clicks_yesterday'] / $data['impressions_yesterday'], 4) : 0.0000;
	$t1 = microtime(true);
        $sql = "SELECT SUM(spent) AS spent FROM spend WHERE user_id = ? AND spend_date = '".date('Y-m-d', strtotime("yesterday"))."' AND spent < 0";
	$result = DB::select($sql, array($user->id));
        $t2 = microtime(true);
        Log::info('Got Yesterday Spent in '.round($t2 - $t1, 4).' seconds');	
        $data['spent_yesterday'] = sizeof($result) ? $result[0]->spent * -1 : 0;
        
        $data['cpm_yesterday'] = (float) $data['impressions_yesterday'] ? round($data['spent_yesterday'] / ($data['impressions_yesterday'] / 1000), 4) : 0.00;
        $data['cpc_yesterday'] = (float) $data['clicks_yesterday'] ? round($data['spent_yesterday'] / $data['clicks_yesterday'], 4) : 0.00;
        $t1 = microtime(true);
                $sql = "SELECT *
                FROM campaign_stats
                WHERE campaign_stats.user_id = ?
                AND campaign_stats.stat_date = DATE_SUB(CURDATE(), INTERVAL 1 DAY);";
        $result = DB::select($sql, array($user->id));
        $t2 = microtime(true);
	Log::info('Got Yesterday Active Campaigns in '.round($t2 - $t1, 4).' seconds');
        $data['active_campaigns_yesterday'] = sizeof($result);
        $t1 = microtime(true);
        /* this month */
        $sql = "SELECT SUM(campaign_stats.impressions) AS impressions, SUM(campaign_stats.clicks) AS clicks, campaign_stats.stat_date
                FROM campaign_stats
                WHERE campaign_stats.user_id = ?
                AND campaign_stats.stat_date >= '".date('Y-m-d', strtotime('first day of this month'))."';";
	$result = DB::select($sql, array($user->id));
        $t2 = microtime(true);
	        Log::info('Got This Month in '.round($t2 - $t1, 4).' seconds');
        $data['impressions_this_month'] = sizeof($result) ? $result[0]->impressions : 0;
        $data['clicks_this_month'] = sizeof($result) ? $result[0]->clicks : 0;
        $data['ctr_this_month'] = (float) $data['clicks_this_month'] ? round($data['clicks_this_month'] / $data['impressions_this_month'], 4) : 0.0000;
        $t1 = microtime(true);
        $sql = "SELECT SUM(spent) AS spent FROM spend WHERE user_id = ? AND spend_date >= '".date('Y-m-d', strtotime('first day of this month'))."' AND spent < 0";
	$result = DB::select($sql, array($user->id));
        $t2 = microtime(true);
	        Log::info('Got This Month Spend in '.round($t2 - $t1, 4).' seconds');
        $data['spent_this_month'] = sizeof($result) ? $result[0]->spent * -1: 0;

        $data['cpm_this_month'] = (float) $data['impressions_this_month'] ? round($data['spent_this_month'] / ($data['impressions_this_month'] / 1000), 4) : 0.00;
        $data['cpc_this_month'] = (float) $data['clicks_this_month'] ? round($data['spent_this_month'] / $data['clicks_this_month'], 4) : 0.00;
        $t1 = microtime(true);
                $sql = "SELECT *
                FROM campaign_stats
                WHERE campaign_stats.user_id = ?
                AND campaign_stats.stat_date >= '".date('Y-m-d', strtotime('first day of this month'))."'";
	$result = DB::select($sql, array($user->id));
        $t2 = microtime(true);
	        Log::info('Got This Month Active Campaigns in '.round($t2 - $t1, 4).' seconds');
        $data['active_campaigns_this_month'] = sizeof($result);
        $t1 = microtime(true);
        /* last month */
        $sql = "SELECT SUM(campaign_stats.impressions) AS impressions, SUM(campaign_stats.clicks) AS clicks, campaign_stats.stat_date
                FROM campaign_stats
                WHERE campaign_stats.user_id = ?
                AND campaign_stats.stat_date >= '".date('Y-m-d', strtotime('first day of last month'))."'
                AND campaign_stats.stat_date < '".date('Y-m-d', strtotime('first day of this month'))."';";
	$result = DB::select($sql, array($user->id));
        $t2 = microtime(true);
	        Log::info('Got Last Month in '.round($t2 - $t1, 4).' seconds');
        $data['impressions_last_month'] = sizeof($result) ? $result[0]->impressions : 0;
        $data['clicks_last_month'] = sizeof($result) ? $result[0]->clicks : 0;
        $data['ctr_last_month'] = (float) $data['clicks_last_month'] ? round($data['clicks_last_month'] / $data['impressions_last_month'], 4) : 0.0000;
        $t1 = microtime(true);
        $sql = "SELECT SUM(spent) AS spent FROM spend WHERE user_id = ? 
                AND spend_date >= '".date('Y-m-d', strtotime('first day of last month'))."'
                AND spend_date < '".date('Y-m-d', strtotime('first day of this month'))."' AND spent < 0";
	$result = DB::select($sql, array($user->id));
        $t2 = microtime(true);
	        Log::info('Got Last Month Spent in '.round($t2 - $t1, 4).' seconds');
        $data['spent_last_month'] = sizeof($result) ? $result[0]->spent * -1 : 0;

        $data['cpm_last_month'] = (float) $data['impressions_last_month'] ? round($data['spent_last_month'] / ($data['impressions_last_month'] / 1000), 4) : 0.00;
        $data['cpc_last_month'] = (float) $data['clicks_last_month'] ? round($data['spent_last_month'] / $data['clicks_last_month'], 4) : 0.00;
        $t1 = microtime(true);
        $sql = "SELECT * FROM campaign_stats
                WHERE campaign_stats.user_id = ?
                AND campaign_stats.stat_date >= '".date('Y-m-d', strtotime('first day of last month'))."'
                AND campaign_stats.stat_date < '".date('Y-m-d', strtotime('first day of this month'))."';";
	$result = DB::select($sql, array($user->id));
        $t2 = microtime(true);
	        Log::info('Got Last Month Active Campaigns in '.round($t2 - $t1, 4).' seconds');
        $data['active_campaigns_last_month'] = sizeof($result);
        $t1 = microtime(true);
        /* last thirty days */
        $data['last_thirty_days'] = array();
        for($i = 60; $i >= 0; $i--){
            $mydate = date('Y-m-d', strtotime("-$i days"));
            
            $sql = "SELECT SUM(campaign_stats.impressions) AS impressions, SUM(campaign_stats.clicks) AS clicks, campaign_stats.stat_date 
                FROM campaign_stats
                WHERE campaign_stats.user_id = ?
                AND campaign_stats.stat_date = ?;";
               $data['last_thirty_days'][date('m/d/Y',strtotime($mydate))] = array('impressions' => 0, 'clicks' => 0, 'spend' => 0);
            foreach(DB::select($sql, array($user->id, $mydate)) as $row){
                $sql = "SELECT spent FROM spend WHERE user_id = ? AND spend_date = ? AND spent < 0";
                $spend = sizeof($daily = DB::select($sql, array($user->id, $mydate))) ? $daily[0]->spent * -1 : 0.00;
                $impressions = intval($row->impressions);
                $clicks = intval($row->clicks);
                $data['last_thirty_days'][date('m/d/Y',strtotime($mydate))] = array('timestamp' => strtotime($mydate) * 1000, 'impressions' => $impressions, 'clicks' => $clicks, 'spend' => $spend);
            }
	}
        $t2 = microtime(true);
	Log::info('Got Last Thirty Days in '.round($t2 - $t1, 4).' seconds');
        if(1){	
        /* campaigns - this month */
        $data['campaigns']['thismonth'] = array();
        $data['campaigns']['lastmonth'] = array();
        $sql = "SELECT COUNT(DISTINCT(creatives.id)) as totalCreatives, campaigns.campaign_name, campaigns.status, campaigns.created_at, campaigns.campaign_type, campaigns.bid,
		SUM(campaign_stats.impressions) AS impressions, SUM(campaign_stats.clicks) AS clicks, COUNT(DISTINCT(campaign_stats.stat_date)) AS days_active, status_types.description,status_types.classname
			FROM campaign_stats
			JOIN campaigns ON campaign_stats.campaign_id = campaigns.id
			JOIN status_types ON campaigns.status = status_types.id
			LEFT JOIN creatives ON creatives.campaign_id = campaign_stats.campaign_id
		WHERE campaign_stats.user_id = ?
                AND campaign_stats.stat_date >= ?
				AND creatives.status = 1
                GROUP BY campaign_name, bid, status, campaign_type, created_at;";
        foreach(DB::select($sql,array($user->id,date('Y-m-d',strtotime('first day of this month')))) as $camp){
            $data['campaigns']['thismonth'][$camp->campaign_name]['impressions'] = isset($data['campaigns']['thismonth'][$camp->campaign_name]['impressions']) ? $data['campaigns']['thismonth'][$camp->campaign_name]['impressions'] + $camp->impressions : $camp->impressions;
            $data['campaigns']['thismonth'][$camp->campaign_name]['clicks'] = isset($data['campaigns']['thismonth'][$camp->campaign_name]['clicks']) ? $data['campaigns']['thismonth'][$camp->campaign_name]['clicks'] + $camp->clicks : $camp->clicks;
            $data['campaigns']['thismonth'][$camp->campaign_name]['days_active'] = isset($data['campaigns']['thismonth'][$camp->campaign_name]['days_active']) ? $data['campaigns']['thismonth'][$camp->campaign_name]['days_active'] + $camp->days_active : $camp->days_active;
			$data['campaigns']['thismonth'][$camp->campaign_name]['created_at'] = isset($data['campaigns']['thismonth'][$camp->campaign_name]['created_at']) ? $data['campaigns']['thismonth'][$camp->campaign_name]['created_at'] + $camp->created_at : $camp->created_at;
			
            $spent = 0;
            if($camp->campaign_type == 1){
                $spent = ($camp->impressions / 1000) * $camp->bid;
            }
            if($camp->campaign_type == 2){
                $spent = $camp->clicks * $camp->bid;
            }
            $data['campaigns']['thismonth'][$camp->campaign_name]['spend'] = isset($data['campaigns']['thismonth'][$camp->campaign_name]['spend']) ? $data['campaigns']['thismonth'][$camp->campaign_name]['spend'] + $spent : $spent;
	    $data['campaigns']['thismonth'][$camp->campaign_name]['status'] = $camp->description;
	    $data['campaigns']['thismonth'][$camp->campaign_name]['classname'] = $camp->classname;
		//$data['campaigns']['thismonth'][$camp->campaign_name]['totalCreatives'] = $camp->totalCreatives;
	}

        /* campaigns - last month */
        $sql = "SELECT COUNT(DISTINCT(creatives.id)) as totalCreatives, campaigns.campaign_name, campaigns.status, campaigns.created_at, campaigns.campaign_type, campaigns.bid,
		SUM(campaign_stats.impressions) AS impressions, SUM(campaign_stats.clicks) AS clicks, COUNT(DISTINCT(campaign_stats.stat_date)) AS days_active, status_types.description,status_types.classname
			FROM campaign_stats
			JOIN campaigns ON campaign_stats.campaign_id = campaigns.id
			JOIN status_types ON campaigns.status = status_types.id
			LEFT JOIN creatives ON creatives.campaign_id = campaign_stats.campaign_id
		WHERE campaign_stats.user_id = ?
                AND campaign_stats.stat_date BETWEEN ? AND ?
				AND creatives.status = 1
                GROUP BY campaign_name, bid, status, campaign_type, created_at;";
        foreach(DB::select($sql,array($user->id,date('Y-m-d',strtotime('first day of last month')),date('Y-m-d',strtotime('first day of this month')))) as $camp){
            $data['campaigns']['lastmonth'][$camp->campaign_name]['impressions'] = isset($data['campaigns']['lastmonth'][$camp->campaign_name]['impressions']) ? $data['campaigns']['lastmonth'][$camp->campaign_name]['impressions'] + $camp->impressions : $camp->impressions;
            $data['campaigns']['lastmonth'][$camp->campaign_name]['clicks'] = isset($data['campaigns']['lastmonth'][$camp->campaign_name]['clicks']) ? $data['campaigns']['lastmonth'][$camp->campaign_name]['clicks'] + $camp->clicks : $camp->clicks;
            $data['campaigns']['lastmonth'][$camp->campaign_name]['days_active'] = isset($data['campaigns']['lastmonth'][$camp->campaign_name]['days_active']) ? $data['campaigns']['lastmonth'][$camp->campaign_name]['days_active'] + $camp->days_active : $camp->days_active;
			
			$data['campaigns']['lastmonth'][$camp->campaign_name]['created_at'] = isset($data['campaigns']['lastmonth'][$camp->campaign_name]['created_at']) ? $data['campaigns']['lastmonth'][$camp->campaign_name]['created_at'] + $camp->created_at : $camp->created_at;
			
            $spent = 0;
            if($camp->campaign_type == 1){
                $spent = ($camp->impressions / 1000) * $camp->bid;
            }
            if($camp->campaign_type == 2){
                $spent = $camp->clicks * $camp->bid;
            }
            $data['campaigns']['lastmonth'][$camp->campaign_name]['spend'] = isset($data['campaigns']['lastmonth'][$camp->campaign_name]['spend']) ? $data['campaigns']['lastmonth'][$camp->campaign_name]['spend'] + $spent : $spent;
	    $data['campaigns']['lastmonth'][$camp->campaign_name]['status'] = $camp->description;
	    $data['campaigns']['lastmonth'][$camp->campaign_name]['classname'] = $camp->classname;
		//$data['campaigns']['thismonth'][$camp->campaign_name]['totalCreatives'] = $camp->totalCreatives;

        } 
	}else{
          $data['campaigns']['thismonth'] = array();
	  $data['campaigns']['lastmonth'] = array();
	}
        return $data;
    }  
    public function aboutUs()
    {
        return view('about', array('title' => 'About Us'));
    }
    public function readZip($filename)
    {
        try{
        $info = '<p>Contents:</p>';
        $zip = zip_open($filename);
        if (is_resource($zip))
        {
          while ($zip_entry = zip_read($zip))
          {
              $name = zip_entry_name($zip_entry);
              if(strpos($name, '__MACOSX') === false){
                  $info .= "<p>" . zip_entry_name($zip_entry) . "</p>";
              }
              /*
              if (zip_entry_open($zip, $zip_entry))
              {
                  $info .= "File Contents:<br/>";
                  $contents = zip_entry_read($zip_entry);
                  $info .=  "$contents<br />";
                  zip_entry_close($zip_entry);
              }
              */
          }

            zip_close($zip);
        }
        return $info;
        }catch(Exception $e){
            return '';
        }
    }
    public function updatePayout(Request $request)
    {
        $user = Auth::getUser();
	$sql = "INSERT INTO payout_settings (user_id, payment_method, minimum_payout, tax_status, tax_id, created_at, updated_at)
		VALUES(?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE payment_method = ".$request->payment_method.", minimum_payout = ".$request->minimum_payout.", tax_status = ".$request->tax_status.", tax_id = '".$request->tax_id."', updated_at = NOW()";
        DB::insert($sql, array($user->id, $request->payment_method, $request->minimum_payout, $request->tax_status, $request->tax_id, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')));
	Session::flash('success', 'All Changes Saved!');
	return redirect('/profile');


    }
    public function generateStats()
    {
	Log::info('Generating Stats');
	$user = Auth::getUser();
	$sql = "SELECT * FROM publisher_bookings WHERE pub_id = ? AND booking_date < '2018-04-01'";
	$result = DB::select($sql, array($user->id));
	foreach($result as $row){
            $factor = 9 + mt_rand() / mt_getrandmax() * (10 - 9);;
	    //$cpm = 4.20;
	    //Log::info('factor = 4.20 * '.$factor.' or '.(4.20 * $factor));
	    //$revenue = ($row->impressions / 1000) * $factor;
	    //Log::info("Revenue = $revenue");
	    $sql = "UPDATE publisher_bookings SET revenue = ? WHERE id = ?";
	    DB::update($sql, array($row->revenue * 5, $row->id));
	}
	return true;
	$sql = 'SELECT DISTINCT(stat_date) FROM affiliate_stats WHERE site_id = 5';
	$result = DB::select($sql);
	foreach($result as $row){
		$sql = "SELECT SUM(impressions) AS impressions, SUM(clicks) as clicks, zone_id FROM affiliate_stats WHERE site_id = 5 and stat_date = ? GROUP BY zone_id";
		$info = DB::select($sql, array($row->stat_date));
		foreach($info as $inf){
		    $sql = "INSERT INTO publisher_bookings (site_id, zone_id, pub_id,booking_date,commission_tier,impressions,clicks, created_at,updated_at)
			VALUES(5,".$inf->zone_id.",".$user->id.",'".$row->stat_date."',1,".$inf->impressions.",".$inf->clicks.",NOW(),NOW()) ON DUPLICATE KEY UPDATE impressions = ".$inf->impressions.", clicks = ".$inf->clicks.";";
                    DB::insert($sql);
                    Log::info($sql);
                }
        }
        Log::info('Done!');
        die();
	
	DB::statement("SET sql_mode = '';");
	$sql = "SELECT * FROM sites WHERE user_id = ?";
        $sites = DB::select($sql, array($user->id));
	foreach($sites as $site)
	{
            /* affiliate stats */
	    $sql = "SELECT DISTINCT(stat_date) as stat_date FROM affiliate_stats WHERE site_id = ?";
	    $dates = DB::select($sql,array($site->id));
	    Log::info(sizeof($dates)." dates found in affiliate stats");
	    $stat_date = '2018-03-11';
            while(strtotime($stat_date) < time()){
		    $statsql = "SELECT * FROM affiliate_stats WHERE site_id = ".$site->id." AND stat_date = '".$dates[mt_rand(0, sizeof($dates) -1)]->stat_date."'";
		    Log::info($statsql);
		    $stats = DB::select($statsql);
		    Log::info(sizeof($stats)." stat records found.");
		    foreach($stats as $stat){
			    $insert = array('affiliate_id' => $stat->affiliate_id, 'site_id' => $stat->site_id, 'zone_id' => $stat->zone_id, 'ad_id' => $stat->ad_id, 'cpm' => $stat->cpm, 'country_id' => $stat->country_id, 'state_code' => $stat->state_code, 'city_code' => $stat->city_code, 'platform' => $stat->platform, 'os' => $stat->os, 'browser' => $stat->browser, 'impressions' => mt_rand(20000,40000), 'clicks' => mt_rand(100,400), 'stat_date' => $stat_date);
	                    $insert = "INSERT INTO affiliate_stats (affiliate_id,site_id,zone_id,ad_id,cpm,country_id,state_code,city_code,platform,os,browser,impressions,clicks,stat_date)
				       VALUES(".$stat->affiliate_id.",".$stat->site_id.",".$stat->zone_id.",".$stat->ad_id.",".$stat->cpm.",".$stat->country_id.",".$stat->state_code.",".$stat->city_code.",".$stat->platform.",".$stat->os.",".$stat->browser.",".mt_rand(20000,40000).",".mt_rand(100,400).",'$stat_date') ON DUPLICATE KEY UPDATE impressions = ".mt_rand(20000,40000).", clicks = ".mt_rand(100,400).";";
			    try{ DB::insert($insert); }catch(Exception $e){Log::error($e->getMessage());}
			    Log::info($insert);
		    } 
		    $stat_date = date('Y-m-d', strtotime($stat_date) + 86400);
		    Log::info('Stat Date: '.$stat_date);
	    }	    
	}
    }
    public function myProfile()
    {
	//$this->generateStats();    
        $user = Auth::getUser();
	$countries = Country::all();
	$payout_settings = PayoutSettings::where('user_id', $user->id)->get();
	$payment_methods = PaymentMethod::all();
	$tax_status = TaxStatus::all();
	$minimum_payouts = MinimumPayout::all();
	
        $payments = array();
        $invoices = array();
        $earnings = array();
        $spend = array();
	$balance = 0;
	$mtd = 0.00;
        /* is user a publisher? */
	$pub = false;
	$earnings = array();
	$current_earnings = 0.00;
	DB::statement("SET sql_mode = ''");
        $sites = Site::where('user_id', $user->id)->count();
        if($sites){
            /* yes, user is a publisher */
            $pub = true;
            /* get payout history */
            $payments = Payment::where('user_id', $user->id)
                               ->where('status', 1)
                               ->where('amount', '>', 0.00)
                               ->orderby('transaction_date', 'desc')->get();
            /* get current unpaid earnings */
            $sql = 'SELECT SUM(pb.revenue) as revenue, (SUM(pb.revenue) * ct.publisher_factor) AS earnings, pb.commission_tier, sites.site_name
                    FROM publisher_bookings pb 
                    JOIN commission_tiers ct
                    ON pb.commission_tier = ct.id
                    JOIN sites 
                    ON pb.site_id = sites.id
                    WHERE pb.pub_id = ?
                    AND pb.cost = 0.00
                    GROUP BY pb.site_id, pb.commission_tier, ct.publisher_factor;';
            $earnings = DB::select($sql, array($user->id));
            foreach($earnings as $earned){ $current_earnings += $earned->earnings; }
        }
        /* is user an advertiser? */
        $buyer = false;
        $campaigns = Campaign::where('user_id', $user->id)->count();
        if($campaigns){
            /* yes, user is an advertiser */
		$buyer = true;
	    /* get month to date spend */
            $sql = "SELECT SUM(transaction_amount) AS spend FROM bank WHERE user_id = ? 
                AND bank.created_at >= '".date('Y-m-d', strtotime('first day of this month'))."'
                AND bank.created_at < '".date('Y-m-d', strtotime('today'))."' AND transaction_amount < 0";
            $result = DB::select($sql, array($user->id));
            $mtd = sizeof($result) ? $result[0]->spend * -1 : 0;

            /* get deposit history */
            $invoices = Transaction::where('user_id', $user->id)
                                   ->where('Status', 'Successful')
                                   ->where('CaptureState', 'Captured')
                                   ->where('TransactionState', 'Captured')
                                   ->where('Amount', '>', 0.00)
                                   ->orderby('transaction_date', 'DESC')->get();
            /* get current balance */
            $sql = "SELECT running_balance AS balance FROM bank WHERE user_id = ? ORDER BY id DESC LIMIT 1";
            $balance = sizeof($bank = DB::select($sql, array($user->id))) ? $bank[0]->balance : 0.00; 
            
        }
        return view('profile', ['user' => $user, 
                                'title' => 'User Profile', 
                                'countries' => $countries,
                                'pub' => $pub,
                                'buyer' => $buyer,
                                'payments' => $payments,
                                'invoices' => $invoices,
                                'earnings' => $earnings,
				'balance' => $balance,
				'payout_settings' => $payout_settings,
				'payment_methods' => $payment_methods,
				'tax_status' => $tax_status,
				'minimum_payouts' => $minimum_payouts,
				'current_earnings' => $current_earnings,
				'mtd' => $mtd,
			        ]);
    }
    public function updateProfile(Request $request)
    {
        $user = Auth::getUser();
	User::where('id', $user->id)->update(array('name' => $request->name, 
		                                   'company' => $request->company,
						   'addr' => $request->addr,
						   'addr2' => $request->addr2,
						   'city' => $request->city,
						   'state' => $request->state,
						   'zip' => $request->zip,
						   'phone' => $request->phone,
						   'country_code' => $request->country,
						   'email' => $request->email));
	Log::info('User '.$user->id.': '.$user->name.' updated their profile.');
	Session::flash('success', 'All Changes Saved!');
	return redirect('/profile');
    }
	
	public function filtered(Request $request)
    {
        $dateRange = explode(' - ', $request->daterange);
        $startDate = Carbon::parse($dateRange[0]);
        $endDate = Carbon::parse($dateRange[1]);
		
        return view('campaigns', compact('startDate', 'endDate'));
    }
	
}
