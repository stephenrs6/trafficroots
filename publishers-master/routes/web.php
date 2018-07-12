<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
// OAuth Routes
Route::get('auth/{driver}', ['as' => 'socialAuth', 'uses' => 'Auth\SocialController@redirectToProvider']);
Route::get('auth/{driver}/callback', ['as' => 'socialAuthCallback', 'uses' => 'Auth\SocialController@handleProviderCallback']);

$landing = function() {
    Route::get('/', 'PublicController@getLandingPage');
    Route::post('/buyer_subscribe', 'PublicController@subscribeUser');
    Route::post('/pub_subscribe', 'PublicController@subscribeUser');
};
Route::group(['domain' => 'www.trafficroots.com'], $landing);
Route::group(['domain' => 'trafficroots.com'], $landing); 

Route::get('/', function () {
    return redirect('/home');
});
Route::get('/privacy', 'PublicController@getPrivacyPage');
Route::post('/update_payout', 'HomeController@updatePayout');
Route::post('/changepw', 'HomeController@pwChange');
Route::get('/pub_type', 'HomeController@pubType');
Route::get('/buyer_type', 'HomeController@buyerType');
Route::get('/both_type', 'HomeController@bothType');
Route::post('/subscribe', 'PublicController@subscribeUser');
Route::get('/landing', 'PublicController@getLandingPage');
Route::get('/pixel/{handle?}', 'PixelController@getIndex');
Route::get('/analysis/{handle}', 'SiteController@analyzeSite');
Route::get('/zone_manage/{handle}', 'ZoneController@manageZone');
Route::get('/custom_ad/{handle}', 'ZoneController@getCustomAd');
Route::post('/custom_ad', 'ZoneController@postCustomAd');
Route::get('/pause_custom_ad/{id}', 'ZoneController@pauseCustomAd');
Route::get('/resume_custom_ad/{id}', 'ZoneController@resumeCustomAd');
Route::patch('/zones/{zone}', 'ZoneController@edit');
Route::get('/edit_custom_ad/{id}', 'ZoneController@editCustomAd');
Route::post('/update_frequencyAd', 'ZoneController@updateFrequencyCap');
Route::post('/update_impressionCap', 'ZoneController@updateImpressionCap');
Route::post('/update_weight', 'ZoneController@updateWeight');
Route::post('/update_start', 'ZoneController@updateStartDate');
Route::post('/update_end', 'ZoneController@updateEndDate');
Route::get('/custom_creatives/{id}', 'ZoneController@createCreative');
Route::get('/edit_custom_creative/{id}', 'ZoneController@editCreative');
Route::post('/edit_custom_creative', 'ZoneController@updateCreative');
Route::post('/update_adTargets', 'CampaignController@updateTargets');
Route::post('/update_adCounties', 'CampaignController@updateCounties');

Auth::routes();
Route::get('/send_confirmation', 'HomeController@sendConfirmation');
Route::get('/charge', 'ChargeController@index');
Route::get('/about', 'PublicController@aboutUs');
Route::get('/home', 'HomeController@index');
Route::get('/advertiser', 'HomeController@advertiser');
Route::get('/campaigns', 'CampaignController@campaigns');
Route::post('/campaigns', 'CampaignController@campaigns');
Route::get('/buyers/{tab?}', 'HomeController@buyers');
Route::get('/sites', 'SiteController@index');
Route::post('/sites', 'SiteController@store');
Route::patch('/sites/{site}', 'SiteController@edit');
Route::patch('/zones/{zone}', 'ZoneController@edit');
Route::get('/stats/site/{site}', 'StatsController@site');
Route::get('/stats/zone/{zone}', 'StatsController@zoneStats');
Route::post('/stats/zone/{zone}', 'StatsController@zoneStats');
Route::post('sites/{site}/zones', 'ZoneController@store');
Route::get('/stats/pub', 'StatsController@pub');
Route::post('/stats/pub', 'StatsController@filtered');
Route::get('/stats/campaign/{id}', 'StatsController@campaignStats');
Route::post('/stats/campaign/{id}', 'StatsController@campaignStats');
Route::post('/stats/campaign', 'StatsController@campaign');
Route::get('/getzones/{site_id}', 'ZoneController@getZones');
Route::post('/getzones', 'ZoneController@postGetZones');
Route::get('/addzone/{site_id}', 'ZoneController@addZone');
Route::post('/addzone/{site_id}', 'ZoneController@postZone');
Route::get('/stats/zone/{zone_id}/{range}', 'StatsController@getZoneStats');
Route::post('/stats/zone/{zone_id}', 'StatsController@getZoneStats');
// Route::get('/stats/site/{site_id}/{range}', 'StatsController@getSiteStats');
Route::get('/tickets', 'TicketController@index');
Route::post('/tickets', 'TicketController@store');
Route::get('/ticket/{id}', 'TicketController@show');
Route::post('/reply', 'TicketController@reply');
Route::get('/service/{handle}/{keywords?}', 'AdserverController@getIndex');
Route::get('/click/{querystr}', 'AdserverController@clickedMe');
Route::get('/profile', 'HomeController@myProfile');
Route::get('/confirm/{handle}', 'ConfirmController@confirm');
Route::get('/campaign', 'CampaignController@createCampaign');
Route::get('/campaign/start/{id}', 'CampaignController@startCampaign');
Route::get('/campaign/pause/{id}', 'CampaignController@pauseCampaign');
Route::post('/campaign', 'CampaignController@postCampaign');
Route::get('/media', 'CampaignController@createMedia');
Route::post('/media', 'CampaignController@postMedia');
Route::get('/getmedia', 'CampaignController@getUserMedia');
Route::get('/edit_media/{id}', 'CampaignController@editMedia');
Route::patch('/edit_media', 'CampaignController@updateMedia');
Route::get('/links', 'CampaignController@createLink');
Route::post('/links', 'CampaignController@postLink');
Route::patch('/edit_link', 'CampaignController@editLink');
Route::get('manage_campaign/{id}', 'CampaignController@editCampaign');
Route::get('/creatives/{id}', 'CampaignController@createCreative');
Route::post('/creatives', 'CampaignController@postCreative');
Route::get('/edit_creative/{id}', 'CampaignController@editCreative');
Route::post('/edit_creative', 'CampaignController@updateCreative');
Route::post('/update_targets', 'CampaignController@updateTargets');
Route::post('/update_bid', 'CampaignController@updateBid');
Route::post('/view_bid', 'CampaignController@viewBids');
Route::post('/update_budget', 'CampaignController@updateBudget');
Route::post('/update_counties', 'CampaignController@updateCounties');
Route::post('/update_frequency', 'CampaignController@updateFrequencyCap');
Route::post('/load_counties', 'CampaignController@loadCounties');
Route::get('/folder', 'CampaignController@createFolder');
Route::post('/folder', 'CampaignController@postFolder');
Route::get('/whoami', 'HomeController@whoAmI');
Route::get('/activate_bid/{id}', 'SiteController@activateBid');
Route::get('/decline_bid/{id}', 'SiteController@declineBid');
Route::get('/preview/{id}', 'SiteController@previewBid');
Route::get('/zone_preview/{handle}', 'SiteController@previewZone');
Route::get('/library', 'HomeController@getLibrary');
Route::get('/faq_advertiser', 'HomeController@advertiserFaq');
Route::get('/faq_publisher', 'HomeController@publisherFaq');
Route::post('/update_profile', 'HomeController@updateProfile');
Route::get('/tradm', 'AdminController@getAdminPage');
Route::post('/tradmlogin', 'AdminController@postLoginFromAdmin');
/* paypal routes */
Route::get('/paywithpaypal', array('as' => 'addmoney.paywithpaypal','uses' => 'AddMoneyController@payWithPaypal',));
Route::post('paypal', array('as' => 'addmoney.paypal','uses' => 'AddMoneyController@postPaymentWithpaypal',));
Route::get('paypal', array('as' => 'payment.status','uses' => 'AddMoneyController@getPaymentStatus',));

/* velocity routes */
Route::get('/addfunds', array('as' => 'addmoney.paybycceftjs', 'uses' => 'CreditAchController@getIndex',));
Route::post('/deposit', array('as' => 'addmoney.deposit', 'uses' => 'CreditAchController@depositFunds',));
//Route::get('/addfunds', array('as' => 'addmoney.paybycceft', 'uses' => 'CreditAchController@getIndex',));
//Route::post('/addfunds', array('as' => 'addmoney.postcceft', 'uses' => 'CreditAchController@postMoney',));
Route::get('/terms', function () { return view('terms'); });
