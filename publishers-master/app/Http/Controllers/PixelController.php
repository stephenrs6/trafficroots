<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Log;
use DB;
use MobileDetect;
use GeoIp2\Database\Reader;
use App\Site;

class PixelController extends Controller
{
    public function __construct()
    {
        $this->mmdb = new Reader('/geoip/GeoLite2-City.mmdb'); // Where my DB resides
        
    }
    public function getUser(Request $request)
    {
        $ip = $request->ip();
        if(strpos($ip,'192.168.') === 0){
            $ip = '72.67.10.103';
        }
        $result = $this->mmdb->city($ip);
        $md = array();
        $md['ip'] = $ip;
        $md['isMobile'] = MobileDetect::isMobile();
        $md['isTablet'] = MobileDetect::isTablet();

        if((!$md['isMobile']) && (!$md['isTablet'])){
            /* it's a desktop or maybe a smart tv*/
            $stuff = explode(' ', $this->getOS());
            $md['os'] = $stuff[0];
            $device = 'Desktop';
        }else{
            if($md['isMobile']){
                /* it's a phone */
                $md['isiPhone'] = MobileDetect::isiPhone();
                if(!$md['isiPhone']){
                    $md['isAndroid'] = MobileDetect::isAndroidOS();
                    if($md['isAndroid']){
                        $md['os'] = 'Android';
                    }else{
                        $md['os'] = 'Other';
                    }
                 }else{
                    $md['os'] = 'iOS';
                 }
                 $device = 'Mobile';
            }
            if($md['isTablet']){
                /* it's a tablet */
                $md['isiPad'] = MobileDetect::isiPad();
                if(!$md['isiPad']){
                    $md['isAndroid'] = MobileDetect::isAndroidOS();
                    if($md['isAndroid']){
                        $md['os'] = 'Android';
                    }else{
                        $md['os'] = 'Other';
                    }
                }else{
                    $md['os'] = 'iOS';
                }
                $device = 'Tablet';
            }

        }

        /* browsers */
        $browser_set = false;
        if(!$browser_set && MobileDetect::isChrome()){
            $md['browser'] = 'Chrome';
            $md['Chrome Version'] = MobileDetect::version('Chrome');
            $browser_set = true;
        }
        if(!$browser_set && MobileDetect::isIE()){
            $md['browser'] = 'Internet Explorer';
            $md['IE Version'] = MobileDetect::version('IE');
            $browser_set = true;
        }
        if(!$browser_set && MobileDetect::isEdge()){
            $md['browser'] = 'Edge';
            $md['Edge Version'] = MobileDetect::version('Edge');
            $browser_set = true;
        }
        if(!$browser_set && MobileDetect::isFirefox()){
            $md['browser'] = 'Firefox';
            $md['Firefox Version'] = MobileDetect::version('Firefox');
            $browser_set = true;
        }
        if(!$browser_set && MobileDetect::isSafari()){
            $md['browser'] = 'Safari';
            $md['Safari Version'] = MobileDetect::version('Safari');
            $browser_set = true;
        }
        if(!$browser_set && MobileDetect::isOpera()){
            $md['browser'] = 'Opera';
            $md['Opera Version'] = MobileDetect::version('Opera');
            $browser_set = true;
        }
        if(!$browser_set && MobileDetect::isUCbrowser()){
            $md['browser'] = 'UC Browser';
            $browser_set = true;
        }
        if(!$browser_set && MobileDetect::isGenericBrowser()){
            $md['browser'] = 'Generic Browser';
            $browser_set = true;
        }

        if(!$browser_set){
            /* still don't know what browser yet ... */
            if(!$browser_set && MobileDetect::version('Chrome')){
                $md['Chrome Version'] = MobileDetect::version('Chrome');
                $md['browser'] = 'Chrome';
                $browser_set = true;
            }
            if(!$browser_set && MobileDetect::version('Firefox')){
                $md['Firefox Version'] = MobileDetect::version('Firefox');
                $md['browser'] = 'Firefox';
                $browser_set = true;
            }
            if(!$browser_set && MobileDetect::version('Edge')){
                $md['Edge Version'] = MobileDetect::version('Edge');
                $md['browser'] = 'Edge';
                $browser_set = true;
            }
            if(!$browser_set && MobileDetect::version('IE')){
                $md['IE Version'] = MobileDetect::version('IE');
                $md['browser'] = 'IE';
                $browser_set = true;
            }
            if(!$browser_set && MobileDetect::version('Safari')){
                $md['Safari Version'] = MobileDetect::version('Safari');
                $md['browser'] = 'Safari';
                $browser_set = true;
            }
            if(!$browser_set && MobileDetect::version('Opera')){
                $md['Opera Version'] = MobileDetect::version('Opera');
                $md['browser'] = 'Opera';
                $browser_set = true;
            }
        }
        $md['platform'] = $device;
        $md['geo'] = $result->country->isoCode;
        $md['state'] = $result->mostSpecificSubdivision->name;
        $md['city'] = $result->city->name;
        return $md;
    
    }
    public function getIndex(Request $request)
    {
        $ip = $request->ip();
        if(strpos($ip,'192.168.') === 0){
            $ip = '72.67.10.103';
        }
        $result = $this->mmdb->city($ip);
        $md = array();
        $md['isMobile'] = MobileDetect::isMobile();
        $md['isTablet'] = MobileDetect::isTablet();
        
        if((!$md['isMobile']) && (!$md['isTablet'])){
            /* it's a desktop or maybe a smart tv*/
            $stuff = explode(' ', $this->getOS());
            $md['os'] = $stuff[0];
            $device = 'Desktop';
        }else{
            if($md['isMobile']){
                /* it's a phone */
                $md['isiPhone'] = MobileDetect::isiPhone();
                if(!$md['isiPhone']){
                    $md['isAndroid'] = MobileDetect::isAndroidOS();
                    if($md['isAndroid']){
                        $md['os'] = 'Android';
                    }else{
                        $md['os'] = 'Other';
                    }
                 }else{
                    $md['os'] = 'iOS';
                 }
                 $device = 'Mobile';
            }
            if($md['isTablet']){
                /* it's a tablet */
                $md['isiPad'] = MobileDetect::isiPad();
                if(!$md['isiPad']){
                    $md['isAndroid'] = MobileDetect::isAndroidOS();
                    if($md['isAndroid']){
                        $md['os'] = 'Android';
                    }else{
                        $md['os'] = 'Other';
                    }
                }else{
                    $md['os'] = 'iOS';
                }
                $device = 'Tablet';
            }

        }
        /* browsers */
        $browser_set = false;
        if(!$browser_set && MobileDetect::isChrome()){
            $md['browser'] = 'Chrome';
            $md['Chrome Version'] = MobileDetect::version('Chrome');
            $browser_set = true;
        }
        if(!$browser_set && MobileDetect::isIE()){
            $md['browser'] = 'Internet Explorer';
            $md['IE Version'] = MobileDetect::version('IE');
            $browser_set = true;
        }
        if(!$browser_set && MobileDetect::isEdge()){
            $md['browser'] = 'Edge';
            $md['Edge Version'] = MobileDetect::version('Edge');
            $browser_set = true;
        }
        if(!$browser_set && MobileDetect::isFirefox()){
            $md['browser'] = 'Firefox';
            $md['Firefox Version'] = MobileDetect::version('Firefox');
            $browser_set = true;
        }
        if(!$browser_set && MobileDetect::isSafari()){
            $md['browser'] = 'Safari';
            $md['Safari Version'] = MobileDetect::version('Safari');
            $browser_set = true;
        }
        if(!$browser_set && MobileDetect::isOpera()){
            $md['browser'] = 'Opera';
            $md['Opera Version'] = MobileDetect::version('Opera');
            $browser_set = true;
        }
        if(!$browser_set && MobileDetect::isUCbrowser()){
            $md['browser'] = 'UC Browser';
            $browser_set = true;
        }
        if(!$browser_set && MobileDetect::isGenericBrowser()){
            $md['browser'] = 'Generic Browser';
            $browser_set = true;
        }      

        if(!$browser_set){
            /* still don't know what browser yet ... */
            if(!$browser_set && MobileDetect::version('Chrome')){            
                $md['Chrome Version'] = MobileDetect::version('Chrome');
                $md['browser'] = 'Chrome';
                $browser_set = true;
            }
            if(!$browser_set && MobileDetect::version('Firefox')){        
                $md['Firefox Version'] = MobileDetect::version('Firefox');
                $md['browser'] = 'Firefox';
                $browser_set = true;
            } 
            if(!$browser_set && MobileDetect::version('Edge')){        
                $md['Edge Version'] = MobileDetect::version('Edge');
                $md['browser'] = 'Edge';
                $browser_set = true;
            }
            if(!$browser_set && MobileDetect::version('IE')){        
                $md['IE Version'] = MobileDetect::version('IE');
                $md['browser'] = 'IE';
                $browser_set = true;
            }
            if(!$browser_set && MobileDetect::version('Safari')){        
                $md['Safari Version'] = MobileDetect::version('Safari');
                $md['browser'] = 'Safari';
                $browser_set = true;
            }
            if(!$browser_set && MobileDetect::version('Opera')){
                $md['Opera Version'] = MobileDetect::version('Opera');
                $md['browser'] = 'Opera';
                $browser_set = true;
            }
        }
        if(!$browser_set) $md['browser'] = 'Other';
        if(isset($request->handle)){
            $site = Site::where('site_handle', $request->handle)->get();
            if(sizeof($site)){           
                $geo = $result->country->isoCode;
                $state = $result->mostSpecificSubdivision->name;
                $city = $result->city->name;
                
                $key = 'SITE|'.$request->handle.'|'.date('Y-m-d').'|'.$geo.'|'.$state.'|'.$city.'|'.$device.'|'.$md['os'].'|'.$md['browser'];
                Cache::increment($key);
            }
                $pngstr = 'R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==';
                $data = base64_decode($pngstr);
                header('Content-Type: image/png');
                echo $data;
                return;

        }
        return view('pixel',['result' => $result, 'md' => $md]);
    }
    
    public function getOS()
    {
        $osList = array (
        /* -- WINDOWS -- */
        'Windows 10 (Windows NT 10.0)' => 'windows nt 10.0',
        'Windows 8.1 (Windows NT 6.3)' => 'windows nt 6.3',
        'Windows 8 (Windows NT 6.2)' => 'windows nt 6.2',
        'Windows 7 (Windows NT 6.1)' => 'windows nt 6.1',
        'Windows Vista (Windows NT 6.0)' => 'windows nt 6.0',
        'Windows Server 2003 (Windows NT 5.2)' => 'windows nt 5.2',
        'Windows XP (Windows NT 5.1)' => 'windows nt 5.1',
        'Windows 2000 sp1 (Windows NT 5.01)' => 'windows nt 5.01',
        'Windows 2000 (Windows NT 5.0)' => 'windows nt 5.0',
        'Windows NT 4.0' => 'windows nt 4.0',
        'Windows Me  (Windows 9x 4.9)' => 'win 9x 4.9',
        'Windows 98' => 'windows 98',
        'Windows 95' => 'windows 95',
        'Windows CE' => 'windows ce',
        'Windows (version unknown)' => 'windows',
        /* -- MAC OS X -- */
        'Mac OS X Beta (Kodiak)' => 'Mac OS X beta',
        'Mac OS X Cheetah' => 'Mac OS X 10.0',
        'Mac OS X Puma' => 'Mac OS X 10.1',
        'Mac OS X Jaguar' => 'Mac OS X 10.2',
        'Mac OS X Panther' => 'Mac OS X 10.3',
        'Mac OS X Tiger' => 'Mac OS X 10.4',
        'Mac OS X Leopard' => 'Mac OS X 10.5',
        'Mac OS X Snow Leopard' => 'Mac OS X 10.6',
        'Mac OS X Lion' => 'Mac OS X 10.7',
        'Mac OS X Mountain Lion' => 'Mac OS X 10.8',
        'Mac OS X Mavericks' => 'Mac OS X 10.9',
        'Mac OS X Yosemite' => 'Mac OS X 10.10',
        'Mac OS X El Capitan' => 'Mac OS X 10.11',
        'macOS Sierra' => 'Mac OS X 10.12',
        'Mac OS X (version unknown)' => 'Mac OS X',
        'Mac OS (classic)' => '(mac_powerpc)|(macintosh)',
        /* -- OTHERS -- */
        'OpenBSD' => 'openbsd',
        'SunOS' => 'sunos',
        'Linux Ubuntu' => 'ubuntu',
        'Linux (or Linux based)' => '(linux)|(x11)',
        'QNX' => 'QNX',
        'BeOS' => 'beos',
        'OS2' => 'os/2',
        'SearchBot'=>'(nuhk)|(googlebot)|(yammybot)|(openbot)|(slurp)|(msnbot)|(ask jeeves/teoma)|(ia_archiver)'
        );

        $useragent = isset($_SERVER['HTTP_USER_AGENT']) ? htmlspecialchars($_SERVER['HTTP_USER_AGENT']) : '';
        $useragent = strtolower($useragent);
        $thisos = "Unknown";
        foreach($osList as $os=>$match) {
           try{
            if (preg_match('/' . preg_quote($match,'/') . '/i', $useragent)) {
                $thisos = $os;
                break;  
            }
          }catch(Exception $e){
              Log::error($e->getMesssage());
          }
        }
        return $os;


    }
}
