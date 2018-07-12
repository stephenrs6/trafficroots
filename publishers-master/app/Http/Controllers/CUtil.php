<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Redis;
use Twilio;
use App\Ad;
use App\Bid;
use App\Creative;
use App\BidCreative;
use App\Browser;
use App\OperatingSystem;
use App\Platform;
use App\City;
use App\State;
use App\Country;
use App\Media;
use App\Campaign;
use App\CampaignType;
use App\Category;
use App\LocationType;
use App\ModuleType;
use App\StatusType;
use App\Links;
use App\SiteTheme;
use Log;
use App\SystemLog;

class CUtil extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function logit($log_entry)
    {
        SystemLog::create(['log' => $log_entry, 'created_at' => date('Y-m-d H:i:s')]);
    }
    public function sendText($msg = 'testing', $recipient = '+19514915526')
    {
        try{
              Twilio::message($recipient, $msg);
              Log::info( 'Text sent to '.$recipient );   
	}catch(Exception $e){
            Log::error( $e->getMessage() );
        }
    }

    public function getThemes($id)
	        {
		        $targets = DB::table('campaign_targets')->where('campaign_id', $id)->first();
		        $theme_targets = explode("|",$targets->themes);
		        $themes = '<option value="0"';
			if($theme_targets[0] == '0') $themes .= ' selected';
        	        $themes .= '>All Sites</option>';
		        $result = SiteTheme::orderBy('theme')->get();;
		        foreach($result as $row){
		            $themes .= '<option value="'.$row->id.'"';
	                    if(in_array($row->id, $theme_targets)) $themes .= ' selected';
                            $themes .= '>'.$row->theme.'</option>';
			}

		        return $themes;

		}
	public function getCountries($id)
    {
        $targets = DB::table('campaign_targets')->where('campaign_id', $id)->first();
        $country_targets = explode("|",$targets->countries);
        $countries = '<option value="0"';
        if($country_targets[0] == '0') $countries .= ' selected';
        $countries .= '>All Countries</option>';
        $result = Country::orderby('id', 'DESC')->whereIn('id', [840, 124])->get();
        foreach($result as $row){
			$countries .= '<option value="'.$row->id.'"';
			if(in_array($row->id, $country_targets)) $countries .= ' selected';
			$countries .= '>'.$row->country_short.' - '.$row->country_name.'</option>';
        }
		
		$result = Country::whereNotIn('id', [840, 124])->orderBy('country_short')->get();
		foreach($result as $row){
			$countries .= '<option value="'.$row->id.'"';
			if(in_array($row->id, $country_targets)) $countries .= ' selected';
			$countries .= '>'.$row->country_short.' - '.$row->country_name.'</option>';
        }

        return $countries;

    }
	
	
    public function getStates($id)
    {
        $targets = DB::table('campaign_targets')->where('campaign_id', $id)->first();
        $state_targets = explode("|",$targets->states);
		//$country_targets = implode(",",explode("|",$targets->countries));
        $states = '<option value="0"';
        if($state_targets[0] == '0') $states .= ' selected';
        $states .= '>All States</option>';

		$result = State::where('country_id', 840)->get();
        foreach($result as $row){
            $states .= '<option value="'.$row->id.'"';
            if(in_array($row->id, $state_targets)) $states .= ' selected';
				$states .= '>'.$row->state_name.'</option>';
        }
		
		$result = State::where('country_id', '<>', 840)->orderby('country_id')->orderby('state_name')->get();
		foreach($result as $row){
			$states .= '<option value="'.$row->id.'"';
            if(in_array($row->id, $state_targets)) $states .= ' selected';
				$states .= '>'.$row->state_name.'</option>';
		}	
		
        return $states;

    }
	
	
	
	
    public function getCounties($id)
    {
        $targets = DB::table('campaign_targets')->where('campaign_id', $id)->first();
	$county_targets = explode("|",$targets->counties);
	$state_targets = implode(",",explode("|",$targets->states));
        $counties = '<option value="0"';
        if($county_targets[0] == '0') $counties .= ' selected';
        $counties .= '>All Counties</option>';
	$sql = "SELECT DISTINCT(county) AS county_name, state_code FROM trafficroots.zips WHERE county <> '' AND state_code IN ($state_targets) ORDER BY state_code, county_name";
	$result = DB::select($sql);
	Log::info($sql);
        foreach($result as $row){
            $counties .= '<option value="'.$row->county_name.'"';
            if(in_array($row->county_name, $county_targets)) $counties .= ' selected';
            $counties .= '>'.$row->county_name.'</option>';
        }

        return $counties;

    }
    public function loadCounties($request)
    {
	if(is_array($request->states)){
            $state_targets = implode(",",$request->states);
	}else{
            $state_targets = $request->states;
	}
        $counties = '<option value="0"';
        if($state_targets == '0') $counties .= ' selected';
        $counties .= '>All Counties</option>';
	$sql = "SELECT DISTINCT(county) AS county_name, state_code FROM trafficroots.zips WHERE county <> '' AND state_code IN ($state_targets) ORDER BY state_code, county_name";
	$result = DB::select($sql);
	Log::info($sql);
        foreach($result as $row){
            $counties .= '<option value="'.$row->county_name.'"';
            $counties .= '>'.$row->county_name.'</option>';
        }

        return $counties;

    }

    public function getCampaignTypes()
    {
        $campaign_types = array();
        $campaign_types[1] = 'CPM';
        $campaign_types[2] = 'CPC';
        return $campaign_types;
    }
    public function getCategories()
    {
        $categories = Category::all();
        $category = array();
        foreach($categories as $cat){
            $category[$cat['id']] = $cat['category'];
        }
        return $category;
    }
    public function getStatusTypes()
    {
        $status_types = array();
        $status = StatusType::all();
        $status_types[] = 'Pending';
        foreach($status as $s){
            $status_types[$s->id] = $s->description;
        }
        return $status_types;
    }
    public function getLocationTypes()
    {    
        $location_types = LocationType::all();
        $location = array();
        foreach($location_types as $type){
            $location[$type['id']] = $type['description'] .' - '.$type['width'].'x'.$type['height'];
        }
        return $location;
    }
    public function getOperatingSystems($id)
    {
        $targets = DB::table('campaign_targets')->where('campaign_id', $id)->first();
        $os_targets = explode("|",$targets->operating_systems);
        $systems = OperatingSystem::orderBy('os')->get();
        $operating_systems = '<option value="0"';
        if($os_targets[0] == '0') $operating_systems .= ' selected';
        $operating_systems .= '>All Operating Systems</option>';
        foreach($systems as $row){
            $operating_systems .= '<option value="'.$row->id.'"';
            if(in_array($row->id, $os_targets)) $operating_systems .= ' selected';
            $operating_systems .= '>'.$row->os.'</option>';
        }
        return $operating_systems;
    }   
    public function getBrowsers($id)
    {
        $targets = DB::table('campaign_targets')->where('campaign_id', $id)->first();
        $b_targets = explode("|",$targets->browsers);
        $browsers = Browser::orderBy('browser')->get();
        $browser_targets = '<option value="0"';
        if($b_targets[0] == '0') $browser_targets .= ' selected';
        $browser_targets .= '>All Browsers</option>';
        foreach($browsers as $row){
            $browser_targets .= '<option value="'.$row->id.'"';
            if(in_array($row->id, $b_targets)) $browser_targets .= ' selected';
            $browser_targets .= '>'.$row->browser.'</option>';
        }
        return $browser_targets;;
    }
    public function getPlatforms($id)
    {
        $targets = DB::table('campaign_targets')->where('campaign_id', $id)->first();
        $p_targets = explode("|",$targets->platforms);
        $platforms = Platform::all();
        $platform_targets = '<option value="0"';
        if($p_targets[0] == '0') $platform_targets .= ' selected';
        $platform_targets .= '>All Platforms</option>';
        foreach($platforms as $row){
            $platform_targets .= '<option value="'.$row->id.'"';
            if(in_array($row->id, $p_targets)) $platform_targets .= ' selected';
            $platform_targets .= '>'.$row->platform.'</option>';
        }
        return $platform_targets;
    }

}
