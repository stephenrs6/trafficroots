<?php
namespace App\Http\Controllers;

use App\Http\Controllers\CUtil;
use App\Http\Controllers\CreditAchController;
use Illuminate\Http\Request;
use App\Ad;
use App\Bank;
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
use App\Folder;
use App\Campaign;
use App\CampaignTarget;
use App\CampaignType;
use App\Category;
use App\LocationType;
use App\ModuleType;
use App\StatusType;
use App\Links;
use App\SiteTheme;
use DB;
use Log;
use Auth;
use Validator;
use Input;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class CampaignController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getUserMedia(Request $request)
    {
        $user = Auth::getUser();
        if($request->has('category') && intval($request->category) && $request->has('location_type') && intval($request->location_type)){
            $response = array();
            if($user->allow_folders){
                $response['folders'] = "<option value=''>Choose One</option>";
                foreach(Folder::where('user_id', $user->id)->where('location_type', intval($request->location_type))->where('category', intval($request->category))->get() as $folder){
                    $response['folders'] .= "<option value='".$folder->id."'>".$folder->folder_name."</option>";
                }
            }else{
                $response['folders'] = '';
            }
            $response['media'] = "<option value=''>Choose One</option>";
            $response['links'] = "<option value=''>Choose One</option>";
            foreach(Media::where('user_id', $user->id)->where('location_type', intval($request->location_type))->where('category', intval($request->category))->orderby('media_name')->get() as $media){
                $response['media'] .= "<option value='".$media->id."'>".$media->media_name."</option>";
            }
            foreach(Links::where('user_id', $user->id)->where('category', intval($request->category))->orderby('link_name')->get() as $link){
                $response['links'] .= "<option value='".$link->id."'>".$link->link_name."</option>";
            }            
            return response()->json($response); 
        }
    }
    public function updateBid(Request $request)
    {
        Log::info('Began updating bid '.$request->camp_id);
        try{
            $bid = (float) $request->bid;
            if($bid){
                $user = Auth::getUser();
		$campaign = intval($request->camp_id);
		$camp = Campaign::where('id', $campaign)->where('user_id', $user->id)->first();
		$topbid = $this->getTopBid($camp['location_type']);

	        if($topbid == 0){
			$return['bid_range'] = 'Your bid of $'.$request->bid.' is in the top 5% of all bids for this location type!';
			$return['bid_class'] = 'success';
	        }else{
                    $ratio = $request->bid / $topbid;
	            if($ratio > .95){
			    $return['bid_range'] = 'Your bid of $'.$request->bid.' is in the top 5% of all bids for this location type!';
			    $return['bid_class'] = 'success';
	            }elseif($ratio > .90){
			    $return['bid_range'] = 'Your bid of $'.$request->bid.' is in the top 10% of all bids for this location type!';
			    $return['bid_class'] = 'info';
	            }elseif($ratio > .80){
			    $return['bid_range'] = 'Your bid of $'.$request->bid.' is in the top 20% of all bids for this location type.';
			    $return['bid_class'] = 'warning';
	            }else{
			    $return['bid_range'] = 'Your bid of $'.$request->bid.' is OUTSIDE the top 20% of all bids for this location type.';
			    $return['bid_class'] = 'danger';
                    }
	        }	
		Campaign::where('id', $campaign)->where('user_id', $user->id)->update(array('bid' => $bid));
		$return['result'] ='Bid Updated to $'.$bid;
		return response()->json($return);
            }else{
                /* bid evaluates to false - invalid */
		    $return['result'] = 'Invalid Bid';
		    return response()->json($return);
            }
        }catch(Exception $e){
            return $e->getMessage();
        } 
    }
    public function updateBudget(Request $request)
    {
	$user = Auth::getUser();
        try{
            $budget = (float) $request->daily_budget;
            if($budget){
                $campaign = intval($request->camp_id);
		Campaign::where('id', $campaign)->where('user_id', $user->id)->update(array('daily_budget' => $budget));
		Log::info($user->name.' updated daily budget for campaign '.$request->camp_id.' to $'.$budget);
                return('All Changes Saved');
            }else{
                /* bid evaluates to false - invalid */
                return('Invalid Budget');
																		                }
         }catch(Exception $e){
                return $e->getMessage();
 	 } 
    }
	
	public function updateFrequencyCap(Request $request)
    {
	$user = Auth::getUser();
        try{
            $frequency = (float)($request->frequency_cap);
            if($frequency){
                $campaign = intval($request->camp_id);
				Campaign::where('id', $campaign)->where('user_id', $user->id)->update(array('frequency_capping' => $frequency));
				Log::info($user->name.' updated Frequency Capping for campaign '.$request->camp_id.' to $'.$frequency);
                return('All Changes Saved');
            }else{
                /* bid evaluates to false - invalid */
                return('Invalid Frequency Capping');
			}
         }catch(Exception $e){
                return $e->getMessage();
 	 	} 
    }
	
    public function updateCounties(Request $request)
    {
		try{
            $this->updateTargets($request);
			$CUtil = new CUtil();
			$output = $CUtil->getCounties($request->campaign_id);
            return($output);
		}catch(Throwable $t){
            Log::error($t->getMessage());
	}
    }
    public function loadCounties(Request $request)
    {
        $CUtil = new CUtil();
	return $CUtil->loadCounties($request);
    }
    public function updateTargets(Request $request)
    {
        try {
            $user = Auth::getUser();
	    $data = array();
            if(is_array($request->themes)){
		$data['themes'] = implode("|",$request->themes);
            }else{
                $data['themes'] = ''.$request->themes;
	    }
	    if(is_array($request->countries)){
		$data['countries'] = implode("|",$request->countries);
	    }else{
		$data['countries'] = ''.$request->countries;
            }
            if (is_array($request->states)) {
                $data['states'] = implode("|", $request->states);
            } else {
                $data['states'] = ''.intval($request->states);
            }
            if (is_array($request->counties)) {
                $data['counties'] = implode("|", $request->counties);
            } else {
                $data['counties'] = ''.intval($request->counties);
            }
	    if (is_array($request->platform_targets)) {
                $data['platforms'] = implode("|", $request->platform_targets);
            } else {
                $data['platforms'] = ''.$request->platform_targets;
            }
            if (is_array($request->operating_systems)) {
                $data['operating_systems'] = implode("|", $request->operating_systems);
            } else {
                $data['operating_systems'] = ''.$request->operating_systems;
            }
            if (is_array($request->browser_targets)) {
                $data['browsers'] = implode("|", $request->browser_targets);
            } else {
                $data['browsers'] = ''.$request->browser_targets;
            }
            if (strlen(trim($request->keyword_targets))) {
                $data['keywords'] = str_replace(",", "|", $request->keyword_targets);
            } else {
                $data['keywords'] = '';
            }
            foreach ($data as $key => $value) {
                if (is_null($value)) {
                    $data[$key] = '0';
                }
           }
           if(isset($request->frequency_capping)) $data['frequency_capping'] = intval($request->frequency_capping);
           $result = DB::select('SELECT * FROM campaign_targets WHERE campaign_id = '.$request->campaign_id);
           if(!sizeof($result)) Log::info("There is no record yet??");
           if(DB::table('campaign_targets')->where('campaign_id', intval($request->campaign_id))->update($data))
           Log::info($user->name.' updated targets on campaign '.$request->campaign_id);
           Log::info(print_r($data, true));
            return('All Changes Saved');
        } catch (Exception $e) {
            return ($e->getMessage);
        }
    }
    public function getCreatives(Request $request)
    {
        $creatives = Creative::where('campaign_id', $request->campaign_id);
        return $creatives;
    }
    public function checkBank()
    {
         $bank = new CreditAchController();
	 $balance = intval($bank->getBalance());
         if(!$balance) return false;
         return true;
    }
	
    public function createCampaign()
    {
	if(!$this->checkBank()) return redirect('/addfunds');
        $campaign_types = CampaignType::all();
		$categories = Category::orderBy('category')->get();
		$themes = SiteTheme::orderBy('theme')->get();
        $location_types = LocationType::orderBy('description')->get();
        $module_types = ModuleType::all();
        
        $countries = '<option value="0" selected>All Countries</option><option value="840">US - United States of America</option><option value="124">CA - Canada</option>';
        $nations = Country::orderBy('country_short')->get();
        foreach($nations as $nation){
	    $countries .= '<option value="'.$nation->id.'">'.$nation->country_short.' - '.$nation->country_name.'</option>';
        }

	$states = '<option value="0" selected>All States</option>';
	$result = State::where('country_id', 840)->get();
        foreach($result as $row){
	    $states .= '<option value="'.$row->id.'">'.State::find($row->id)->country_name['country_name'].' - '.$row->state_name.'</option>';
	}
	$result = State::where('country_id', '<>', 840)->orderby('country_id')->orderby('state_name')->get();
        foreach($result as $row){
	    $states .= '<option value="'.$row->id.'">'.State::find($row->id)->country_name['country_name'].' - '.$row->state_name.'</option>';
	}	
        $counties = '<option value="0" selected>All Counties</option>'; 
        $systems = OperatingSystem::orderBy('os')->get();
        $operating_systems = '<option value="0" selected>All Operating Systems</option>';
        foreach($systems as $row){
            $operating_systems .= '<option value="'.$row->id.'">'.$row->os.'</option>';
        }
        
        $browsers = Browser::orderBy('browser')->get();
        $browser_targets = '<option value="0" selected>All Browsers</option>';
        foreach($browsers as $row){
            $browser_targets .= '<option value="'.$row->id.'">'.$row->browser.'</option>';
        }

        $platforms = Platform::all();
        $platform_targets = '<option value="0" selected>All Platforms</option>';
        foreach($platforms as $row){
            $platform_targets .= '<option value="'.$row->id.'">'.$row->platform.'</option>';
	}
        $user = Auth::getUser();	
	return view('campaign_create', ['user' => $user,
		                       'countries' => $countries,
		                       'campaign_types' => $campaign_types,
				       'categories' => $categories,
				       'themes' => $themes,
                                       'browsers' => $browsers,
                                       'platforms' => $platforms,
                                       'operating_systems' => $operating_systems,
                                       'states' => $states,
                                       'countries' => $countries,
                                       'location_types' => $location_types,
                                       'module_types' => $module_types,
                                       'platforms' => $platform_targets,
                                       'browser_targets' => $browser_targets,
				       'os_targets' => $operating_systems,
				       'counties' => $counties,
                                       'states' => $states]);
    }
    public function postCampaign(Request $request)
    {   
	if(!$this->checkBank()) return redirect('/addfunds');
	try{
        $user = Auth::getUser();
        $campaign = new Campaign();
	$data = $request->all();
	$data['daily_budget'] = floatval($data['daily_budget']);
	Log::info(print_r($data,true));
        $data['user_id'] = $user->id;
        $campaign->fill($data);
        $campaign->save();
	$id = $campaign->id;
	$request->campaign_id = $id;
        $targets = array();
        $targets['campaign_id'] = $campaign->id;
        $targets['user_id'] = $user->id;
        $target = new CampaignTarget();
	$target->fill($targets);
        $target->save();
	$this->updateTargets($request);  
        
        /* look for creatives */
        foreach($data as $key => $value){
            if(strpos($key, 'creative') === 0){
                $info = explode('_', $key);
                $size = sizeof($info);
		if($size == 3){
			/* it's a banner */
			$sql = "INSERT INTO creatives (campaign_id, user_id, description, media_id, link_id, created_at) VALUES(?,?,?,?,?,?);";
			DB::insert($sql, array($id, $user->id, $value, $info[1], $info[2], date('Y-m-d H:i:s')));

		}
		if($size == 2){
                    /* it's a folder */
                        $sql = "INSERT INTO creatives (campaign_id, user_id, description, folder_id, created_at) VALUES(?,?,?,?,?);";
			DB::insert($sql, array($id, $user->id, $value, $info[1], date('Y-m-d H:i:s'))); 
		}
            }
	}	
	$this->balanceCreatives($id);
        $cutil = new CUtil();
	$cutil->logit($user->name.' created campaign '.$id.' from '.$request->ip());
	return json_encode(array('result' => 'OK'));
	}catch(Exception $e){
            Log::error($e->getMessage());
	}
    }
    private function balanceCreatives($id)
    {
	$sql = "SELECT COUNT(*) AS records FROM creatives WHERE campaign_id = $id";
	$result = DB::select($sql);
	$count = $result[0]->records;
	if($count){
	$weight = round(100 / $count);
	$sql = "UPDATE creatives SET weight = ? WHERE campaign_id = ?";
	Log::info($sql);
	DB::update($sql, array($weight, $id));
	}
	return true;
    }
    public function createCreative(Request $request)
    {
        $user = Auth::getUser();
        $campaign = Campaign::where([['id', $request->id],['user_id', $user->id]])->first();
        $creatives = Creative::where([['campaign_id', $request->id],['user_id', $user->id]])->get();
        $media = Media::where([['location_type', $campaign->location_type],['category', $campaign->campaign_category],['user_id', $user->id]])->whereIn('status', [1, 5])->orderby('media_name', 'ASC')->get();
        $folders = Folder::where([['location_type', $campaign->location_type],['user_id', $user->id]])->whereIn('status', [1, 5])->get();
        $links = Links::where([['category', $campaign->campaign_category],['user_id', $user->id]])->whereIn('status', [1, 5])->orderby('link_name', 'ASC')->get();
        return view('new_creative', ['user' => $user, 'campaign' => $campaign, 'media' => $media, 'links' => $links, 'folders' => $folders]);
    }
    public function postCreative(Request $request)
    {
        $user = Auth::getUser();
        $data = $request->all();
        $data['user_id'] = $user->id;
		$data['status'] = 5;
		$data['weight'] = 100;
        if (isset($data['folder_id'])) {
            $data['folder_id'] = intval($data['folder_id']);
        }	
		$creative = new Creative();
		$creative->fill($data);
		$creative->save();
		return redirect('/manage_campaign/'.$request->campaign_id)->with('creative_updated', 'Success! A new creative has been added.');
    }
	
	public function editCreative(Request $request)
    {
		$user = Auth::getUser();
        $creative = Creative::where([['id', $request->id],['user_id', $user->id]])->first();
		$campaign = Campaign::where([['id', $creative->campaign_id],['user_id', $user->id]])->first();
        $currentMedia = Media::where([['id', $creative->media_id],['user_id', $user->id]])->first()->id;
		$media = Media::where([['location_type', $campaign->location_type],['category', $campaign->campaign_category],['user_id', $user->id]])->whereIn('status', [1, 5])->orderby('media_name', 'ASC')->get();
		$currentLink = Links::where([['id', $creative->link_id],['user_id', $user->id]])->first()->id;
	 	$links = Links::where([['category', $campaign->campaign_category],['user_id', $user->id]])->whereIn('status', [1, 5])->orderby('link_name', 'ASC')->get();
		$folders = Folder::where([['id', $creative->folder_id],['user_id', $user->id]])->get();

		return view('edit_creative', ['user' => $user, 'creative' => $creative, 'campaign' => $campaign, 'media' => $media, 'currentMedia' => $currentMedia, 'links' => $links, 'currentLink' => $currentLink, 'folders' => $folders]);
    }
	
	public function updateCreative(Request $request)
    {
		try{
			$user = Auth::getUser();
			$creative = Creative::find($request->creative_id);
			if (!($creative->media_id == $request->media_id)) {
				$creative->status = 5;
			}
			if (!($creative->link_id == $request->link_id)) {
				$creative->status = 5;
			}
			if (isset($request->folder_id)) {
				$creative->folder_id = intval($request->folder_id);
			}
			$creative->media_id = $request->media_id;
			$creative->link_id = $request->link_id;
			$creative->description = $request->description;
			$creative->save();
			
			if ($creative->status == 5) {
				$totalcreatives = Creative::where([['campaign_id', $request->campaign_id],['user_id', $user->id]])->count();
				$campaign = Campaign::where([['id', $request->campaign_id],['user_id', $user->id]])->first();
				if ($campaign){
					if($totalcreatives == 1){
						$update = array('status' => 5, 'updated_at' => DB::raw('NOW()'));
						DB::table('campaigns')->where([['id', $request->campaign_id],['user_id', $user->id]])->update($update);
						Log::info($user->name." Set campaign and creative to pending. Campaign has only 1 creative id ".$request->creative_id);
						return redirect('/manage_campaign/'.$request->campaign_id)->with('creative_updated', 'Success! Creative and Campaign ['.$campaign->campaign_name.'] has been updated and paused. Please allow up to 24 hours for approval.');
					}
				}
				return redirect('/manage_campaign/'.$request->campaign_id)->with('creative_updated', 'Success! Creative has been updated and is paused. Please allow up to 24 hours for approval.');
			}
			
			return redirect('/manage_campaign/'.$request->campaign_id)->with('creative_updated', 'Success! Creative has been updated.');
			
		} catch(Exception $e){
            return $e->getMessage();
        } 
    }

    public function createMedia()
    {
        $location_types = LocationType::orderBy('description')->get();
        $categories = Category::orderBy('category')->get();
        return view('media_upload', ['location_types' => $location_types, 'categories' => $categories]);
    }

    public function postMedia(Request $request)
    {
        try{
        $this->validate($request, [
            'media_name' => 'required|string',
            'image_category' => 'required|exists:categories,id',
            'image_size' => 'required|exists:location_types,id',
            'file' => 'required|mimes:jpeg,gif,png|max:300'
        ]);
	$data = $request->all();
	$data['category'] = $data['image_category'];
	$data['location_type'] = $data['image_size'];
        $media = new Media();
        $user = Auth::getUser();
        $destination = 'uploads/'.$user->id;
        $path = $request->file('file')->store($destination);
        $data['user_id'] = $user->id;
        $data['status'] = 5;
        $data['file_location'] = $path;
        $media->fill($data);
        $media->save();
        if($request->return_url == 'library'){
            $url = '/' . $request->return_url;
            return redirect($url);
        }else{
            return response()->json([
            'result' => 'OK',
            ]);
        }
        }catch(Throwable $t){
            Log::error($t->getMessage());
            return response()->json(['result' => $t->getMessage()]);
        }
    }
	
	public function editMedia(Request $request)
    {
		$user = Auth::getUser();
		$media = Media::where([['id', $request->id],['user_id', $user->id]])->first();
		return view('edit_media', ['user' => $user->id, 'media' => $media]);
     }	     
	
	public function updateMedia(Request $request)
    {
        $this->validate($request, [
            'media_name' => 'required|string'
        ]);
		$user = Auth::getUser();
		$media = Media::find($request->media_id);
		$media->media_name = $request->media_name;
		$media->location_type = $request->image_size;
		$media->category = $request->image_category;
		if ($request->file('file')){
			$destination = 'uploads/'.$user->id;
        	$path = $request->file('file')->store($destination);
			$media->file_location = $path;
			$media->status = 5;
		}
		$media->save();
		
		if ($media) {
			if($media->status == 5){
				$update = array('status' => 5, 'updated_at' => DB::raw('NOW()'));
				DB::table('creatives')->where([['media_id', $request->media_id],['user_id', $user->id]])->update($update);
				Log::info($user->name." Set creatives to pending tied to media id ".$request->media_id);
				$creatives = array();
				$creatives = DB::table('campaigns')
				->join('creatives', 'creatives.campaign_id', '=', 'campaigns.id')
				->join('media', 'media.id', '=', 'creatives.media_id')
				->select('campaigns.campaign_name', 'creatives.campaign_id', 'creatives.description', 'media.media_name')
				->where('media.id', $request->media_id)
				->get();
				if($creatives){
					foreach ($creatives as $creative_camp) {
						$totalcreatives = Creative::where([['campaign_id', $creative_camp->campaign_id],['user_id', $user->id]])->count();
						if($totalcreatives == 1){
							DB::table('campaigns')->where([['id', $creative_camp->campaign_id],['user_id', $user->id]])->update($update);
							Log::info($user->name." Set campaign to pending. Have 1 creative that's tied to a pending media id ".$request->media_id);
							return redirect('/library')->with('media_updated', 'Success! Media and Campaign ['.$creative_camp->campaign_name.'] has been updated and paused. Please allow up to 24 hours for approval.');
						} else {
							return redirect('/library')->with('media_updated', 'Success! Media and Creative ['.$creative_camp->description.'] has been updated and paused. Please refer to Campaign name ['.$creative_camp->campaign_name.'] and allow up to 24 hours for approval.');
						}
					}
				}
				return redirect('/library')->with('media_updated', 'Success! Media has been updated and currently pending. Please allow up to 24 hours for the media to be approved.');
			}
		} 
		return redirect('/library')->with('media_updated', 'Success! Media has been updated.');
    }
	
    public function createFolder()
    {
        $location_types = LocationType::all();
        $categories = Category::all();
        return view('html5_upload', ['location_types' => $location_types, 'categories' => $categories]);
    }

    public function postFolder(Request $request)
    {
        $data = $request->all();
        $folder = new Folder();
        $user = Auth::getUser();
        $destination = 'uploads/'.$user->id;
        $path = $request->file('zfile')->store($destination);
        $data['user_id'] = $user->id;
        $data['file_location'] = $path;
        $folder->fill($data);
        $folder->save();
           // sending back with message
           Session::flash('success', 'Upload completed!');
        return redirect('/buyers');
    }
    public function createLink()
    {
        $categories = Category::all();
        return view('create_link', ['categories' => $categories]);
    }

    public function postLink(Request $request)
    {
        $this->validate($request, [
            'link_name' => 'required|string',
            'link_category' => 'required|exists:categories,id',
            'url' => 'required|url'
        ]);
	$data = $request->all();
	$data['category'] = $data['link_category'];
        $data['user_id'] = Auth::getUser()->id;
        $data['status'] = 5;
        $link = new Links();
        $link->fill($data);
	$link->save();
	if($request->return_url == 'library'){
	    $url = '/' . $request->return_url;
	    return redirect($url);
	}else{
            return response()->json([
            'newid' => $link->id,
            'result' => 'OK',
            ]);
	}
    }
	
	public function editLink(Request $request)
    {
        $this->validate($request, [
            'link_name' => 'required|string',
            'link_category' => 'required|exists:categories,id',
            'url' => 'required|url'
        ]);
		
		$user = Auth::getUser();
		$link = Links::find($request->link_id);
		$link->link_name = $request->link_name;
		$link->category = $request->link_category;
		if (!($link->url == $request->url)){
			$link->status = 5;
		}
		$link->url = $request->url;
		$link->save();
		
		if ($link) {
			if($link->status == 5){
				$update = array('status' => 5, 'updated_at' => DB::raw('NOW()'));
				DB::table('creatives')->where([['link_id', $request->link_id],['user_id', $user->id]])->update($update);
				Log::info($user->name." Set creatives to pending tied to link id ".$request->link_id);
				$creatives = array();
				$creatives = DB::table('campaigns')
				->join('creatives', 'creatives.campaign_id', '=', 'campaigns.id')
				->join('links', 'links.id', '=', 'creatives.link_id')
				->select('campaigns.campaign_name', 'creatives.campaign_id', 'creatives.description', 'links.link_name')
				->where('links.id', $request->link_id)
				->get();
				if($creatives){
					foreach ($creatives as $creative_camp) {
						$totalcreatives = Creative::where([['campaign_id', $creative_camp->campaign_id],['user_id', $user->id]])->count();
						if($totalcreatives == 1){
							DB::table('campaigns')->where([['id', $creative_camp->campaign_id],['user_id', $user->id]])->update($update);
							Log::info($user->name." Set campaign to pending. Have 1 creative that's tied to a pending link id ".$request->link_id);
							return redirect()->back()->with('link_updated', 'Success! Link and Campaign ['.$creative_camp->campaign_name.'] has been updated and paused. Please allow up to 24 hours for approval.');
						} else {
							return redirect()->back()->with('link_updated', 'Success! Link and Creative ['.$creative_camp->description.'] has been updated and paused. Please refer to Campaign name ['.$creative_camp->campaign_name.'] and allow up to 24 hours for approval.');
						}
					}
				}
				return redirect()->back()->with('link_updated', 'Success! Link has been updated and currently pending. Please allow up to 24 hours for the link to be approved.');
			}
		} 
		return redirect()->back()->with('link_updated', 'Success! Media has been updated.');
    }
	
    public function editCampaign(Request $request)
    {
	if(!$this->checkBank()) return redirect('/addfunds');
        $user = Auth::getUser();
        $CUtil = new CUtil();
        $campaign = DB::select('select * from campaigns where id = '.$request->id.' and user_id = '.$user->id);
        if ($campaign) {
            $campaign_types = $CUtil->getCampaignTypes();
	    $category = $CUtil->getCategories();
            $status_types = $CUtil->getStatusTypes();
            $location = $CUtil->getLocationTypes();
	    foreach ($campaign as $camp) {
		$themes = $CUtil->getThemes($camp->id);
		$countries = $CUtil->getCountries($camp->id);
                $states = $CUtil->getStates($camp->id);
                $os_targets = $CUtil->getOperatingSystems($camp->id);
                $platforms = $CUtil->getPlatforms($camp->id);
		$browser_targets = $CUtil->getBrowsers($camp->id);
		$counties = $CUtil->getCounties($camp->id);
                $creatives = Creative::where('campaign_id', $camp->id)->get();
                if (!$creatives) {
                    $creatives = array();
                }
                $row = DB::table('campaign_targets')->where('campaign_id', $camp->id)->first();
                $media = DB::select('select * from media where user_id = '.$user->id.' and location_type = '.$camp->location_type);
		$links = DB::select('select * from links where user_id = '.$user->id.' and category = '.$camp->campaign_category);
			
		/* get bid status */
		$topbid = $this->getTopBid($camp->location_type);

	        if($topbid == 0){
			$bid_range = 'Your bid of $'.$camp->bid.' is in the top 5% of all bids for this location type!';
			$bid_class = 'success';
	        }else{
                    $ratio = $camp->bid / $topbid;
	            if($ratio > .95){
			    $bid_range = 'Your bid of $'.$camp->bid.' is in the top 5% of all bids for this location type!';
			    $bid_class = 'success';
	            }elseif($ratio > .90){
			    $bid_range = 'Your bid of $'.$camp->bid.' is in the top 10% of all bids for this location type!';
			    $bid_class = 'info';
			    
	            }elseif($ratio > .80){
			    $bid_range = 'Your bid of $'.$camp->bid.' is in the top 20% of all bids for this location type.';
			    $bid_class = 'warning';
	            }else{
			    $bid_range = 'Your bid of $'.$camp->bid.' is OUTSIDE the top 20% of all bids for this location type.';
			    $bid_class = 'danger';
                    }
	        }	
			
			$frequencyCapping = '<option value="0">Disabled</option><option value="1">1 Impression Per 24 Hours</option><option value="2">2 Impressions Per 24 Hours</option><option value="3">3 Impressions Per 24 Hours</option><option value="4">4 Impressions Per 24 Hours</option><option value="5">5 Impressions Per 24 Hours</option>';
		
                return view('manage_campaign', [
                    'campaign' => $camp,
					'frequencyCapping' => $frequencyCapping,
                    'media' => $media,
		    'links' => $links,
		    'themes' => $themes,
                    'campaign_types' => $campaign_types,
                    'categories' => $category,
                    'status_types' => $status_types,
                    'location_types' => $location,
                    'creatives' => $creatives,
					'countries' => $countries,
                    'states' => $states,
                    'os_targets' => $os_targets,
                    'browser_targets' => $browser_targets,
                    'platforms' => $platforms,
                    'keywords' => str_replace("|", ",", $row->keywords),
		    'user_id' => $user->id,
		    'bid_range' => $bid_range,
		    'bid_class' => $bid_class,
		    'counties' => $counties
                ]);
            }
        }
    }
	
	public function viewBids(Request $request)
	{
		try{
		$bid = $request->bid_status;
            if($bid){
				$user = Auth::getUser();
				$CUtil = new CUtil();
				$campaign = DB::select('select * from campaigns where campaign_type = '.$request->camp_type.' and location_type = '.$request->camp_location.' and campaign_category = '.$request->camp_category);

				if ($campaign) {
					foreach ($campaign as $camp) {
					/* get bid status */
						$topbid = $this->getTopBid($camp->location_type);

						if($topbid == 0){
						$bid_range = 'Your bid of $'.$bid.' is in the top 5% of all bids for this location type!';
						$bid_class = 'success';
						}else{
								$ratio = $camp->bid / $topbid;
							if($ratio > .95){
							$bid_range = 'Your bid of $'.$bid.' is in the top 5% of all bids for this location type!';
							$bid_class = 'success';
							}elseif($ratio > .90){
							$bid_range = 'Your bid of $'.$bid.' is in the top 10% of all bids for this location type!';
							$bid_class = 'info';

							}elseif($ratio > .80){
							$bid_range = 'Your bid of $'.$bid.' is in the top 20% of all bids for this location type.';
							$bid_class = 'warning';
							}else{
							$bid_range = 'Your bid of $'.$bid.' is OUTSIDE the top 20% of all bids for this location type.';
							$bid_class = 'danger';
							}
						}

						$notification = array(
							'bid_range' => $bid_range, 
							'bid_class' => $bid_class
						);
						
						return response()->json($notification);
					}
				}
			} else{
                $notification = array(
					'bid_range' => "Invalid Bid", 
					'bid_class' => "error"
				);
		    	return response()->json($notification);
            }
		}catch(Exception $e){
			return $e->getMessage();
		} 
	}
	
    public function campaigns(Request $request)
    {
	    ini_set('memory_limit','2048M');
	    $user = Auth::user();
	    if (Gate::allows('unconfirmed_user')) {
		$user = Auth::getUser();
		Log::info($user->name.' attempted to access Campaigns page and got sent home.');
		return redirect('/profile');
	    }
	if($request->has('daterange')){
                $dateRange = explode(' - ', $request->daterange);
	        $startDate = Carbon::parse($dateRange[0]);
		$endDate = Carbon::parse($dateRange[1]);
	}else{
            $startDate = Carbon::now()->toDateString();
            $endDate = Carbon::now()->toDateString();
	}
        $campaigns = Campaign::with(['stats' => function ($query) use ($startDate, $endDate) {
            $query
                ->where('stat_date', '>=', $startDate)
                ->where('stat_date', '<=', $endDate);
        },'status_type','category','type'])
		->where('user_id', $user->id)
		->orderby('campaigns.created_at', 'DESC')
            ->get();
		
        return view(
            'advertiser.campaigns',
            compact('campaigns', 'startDate', 'endDate')
        );
    }
    public function startCampaign(Request $request)
    {
        $user = Auth::user();
        if($request->id){
            $campaign = Campaign::where('id', $request->id)->where('user_id', $user->id)->get();
            if(sizeof($campaign)){
                /* campaign belongs to user */
                $update = array('status' => 1, 'updated_at' => DB::raw('NOW()'));
                DB::table('campaigns')->where('id', $request->id)->update($update);
                DB::table('bids')->where('campaign_id', $request->id)->update($update);
                Log::info($user->name." Activated Campaign ".$request->id);
                return('Campaign Activated!');
            }else{
                return('Campaign Not Found');
            }
        }else{
            return('Invalid Campaign ID!');           
        }

    }
    public function pauseCampaign(Request $request)
    {
        $user = Auth::user();
        if($request->id){
            $campaign = Campaign::where('id', $request->id)->where('user_id', $user->id)->get();
            if(sizeof($campaign)){
                /* campaign belongs to user */
                $update = array('status' => 3, 'updated_at' => DB::raw('NOW()'));
                DB::table('campaigns')->where('id', $request->id)->update($update);
                DB::table('bids')->where('campaign_id', $request->id)->update($update);
                Log::info($user->name." Paused Campaign ".$request->id);
                return('Campaign Paused!');
            }else{
                return('Campaign Not Found');
            }
        }else{
            return('Invalid Campaign ID!');
        }
    }
    public function getBidTips(Request $request)
    {
        $post = $request->all();
	$return = array('minimum' => 0.00, 'topbid' => 0.00);
                
        if($post['campaign_type'] == 1){
            /* cpm campaign */

	}
	if($post['campaign_type'] == 2){
            /* cpc campaign */

	}
	$topbid = $this->getTopBid($post['location_type']);

	if($topbid == 0){
		$return['bid_range'] = 5;
	}else{
             $ratio = $request->bid / $topbid;
	     if($ratio > .95){
		     $return['bid_range'] = 5;
	     }elseif($ratio > .90){
		     $return['bid_range'] = 10;
	     }elseif($ratio > .80){
		     $return['bid_range'] = 20;
	     }else{
		     $return['bid_range'] = 0;
             }
	}
	return response()->json($return);
    }
    public function getTopBid($location_type)
    {
	    try{
		    $topbid = 0;
		    $sql = "SELECT * FROM campaigns 
			    WHERE location_type = ?
			    AND status = 1
                            ORDER BY bid DESC;";
                $result = DB::select($sql, array($location_type));
                foreach($result as $row){
                    $sql = 'SELECT * FROM bank WHERE user_id = ? ORDER BY id DESC LIMIT 1';
		    $bank = DB::select($sql, array($row->user_id));
		    if($bank[0]->running_balance > 0){
                        return $row->bid;
	            }
		}
		return $topbid;
	    }catch(Exception $e){
                return 0.00;
            }

    }
	
	
	public function filtered(Request $request)
    {
        $dateRange = explode(' - ', $request->daterange);
        $startDate = Carbon::parse($dateRange[0]);
        $endDate = Carbon::parse($dateRange[1]);
		
        return view('campaigns', compact('startDate', 'endDate'));
    }
}
